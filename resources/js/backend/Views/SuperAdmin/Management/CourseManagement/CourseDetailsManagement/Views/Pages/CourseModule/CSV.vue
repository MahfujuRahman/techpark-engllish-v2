<template>
    <div class="csv-upload-container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-upload mr-2"></i>
                    Upload Course Modules CSV
                </h5>
            </div>
            <div class="card-body">
                <!-- Sample CSV Download -->
                <div class="mb-4">
                    <h6>Download Sample CSV Template</h6>
                    <p class="text-muted">Download the sample CSV template to understand the required format.</p>
                    <button @click="downloadTemplate" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download mr-1"></i>
                        Download Template
                    </button>
                </div>

                <hr>

                <!-- File Upload Form -->
                <form @submit.prevent="uploadCSV">
                    <div class="form-group">
                        <label for="csvFile" class="form-label">Choose CSV File</label>
                        <input 
                            type="file" 
                            id="csvFile"
                            ref="csvFile"
                            @change="handleFileSelect"
                            class="form-control"
                            accept=".csv"
                            required
                        >
                        <small class="form-text text-muted">
                            Please select a CSV file containing course modules data.
                        </small>
                    </div>

                    <!-- Preview -->
                    <div v-if="fileSelected" class="mb-3">
                        <h6>File Selected:</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-file-csv mr-2"></i>
                            {{ selectedFileName }}
                            <span class="text-muted">({{ formatFileSize(selectedFileSize) }})</span>
                        </div>
                    </div>

                    <!-- Upload Options -->
                    <div class="form-group">
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                id="replaceExisting"
                                v-model="replaceExisting"
                                class="form-check-input"
                            >
                            <label class="form-check-label" for="replaceExisting">
                                Replace existing modules
                            </label>
                            <small class="form-text text-muted d-block">
                                If checked, existing modules will be replaced. Otherwise, new modules will be added.
                            </small>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <button 
                                type="button" 
                                @click="goBack" 
                                class="btn btn-secondary"
                                :disabled="uploading"
                            >
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to Modules
                            </button>
                            
                            <button 
                                type="submit" 
                                class="btn btn-primary"
                                :disabled="uploading || !fileSelected"
                            >
                                <i v-if="uploading" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else class="fas fa-upload mr-1"></i>
                                {{ uploading ? 'Uploading...' : 'Upload CSV' }}
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Upload Progress -->
                <div v-if="uploading" class="mt-3">
                    <div class="progress">
                        <div 
                            class="progress-bar progress-bar-striped progress-bar-animated" 
                            role="progressbar" 
                            :style="{ width: uploadProgress + '%' }"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    CSV Format Instructions
                </h6>
            </div>
            <div class="card-body">
                <h6>Required Columns:</h6>
                <ul class="list-unstyled">
                    <li><strong>milestone_title:</strong> Title of the milestone</li>
                    <li><strong>milestone_no:</strong> Milestone number</li>
                    <li><strong>module_title:</strong> Title of the module</li>
                    <li><strong>module_no:</strong> Module number</li>
                    <li><strong>class_title:</strong> Title of the class</li>
                    <li><strong>class_no:</strong> Class number</li>
                    <li><strong>class_type:</strong> Type of class (live/recorded)</li>
                    <li><strong>class_video_link:</strong> Video link for the class</li>
                    <li><strong>class_date:</strong> Class date (YYYY-MM-DD format)</li>
                    <li><strong>class_time:</strong> Class time (HH:MM format)</li>
                    <li><strong>class_topic:</strong> Topic for the class</li>
                </ul>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Important:</strong> Make sure your CSV file follows the exact column names and formats specified above.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseModuleCSV',
    
    data() {
        return {
            uploading: false,
            uploadProgress: 0,
            fileSelected: false,
            selectedFileName: '',
            selectedFileSize: 0,
            replaceExisting: false
        };
    },
    
    methods: {
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileSelected = true;
                this.selectedFileName = file.name;
                this.selectedFileSize = file.size;
            } else {
                this.fileSelected = false;
                this.selectedFileName = '';
                this.selectedFileSize = 0;
            }
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        async uploadCSV() {
            if (!this.fileSelected) {
                window.s_warning('Please select a CSV file first.');
                return;
            }
            
            this.uploading = true;
            this.uploadProgress = 0;
            
            try {
                const formData = new FormData();
                formData.append('csv_file', this.$refs.csvFile.files[0]);
                formData.append('course_id', this.$route.params.id);
                formData.append('replace_existing', this.replaceExisting ? '1' : '0');
                
                // Simulate upload progress
                const progressInterval = setInterval(() => {
                    if (this.uploadProgress < 90) {
                        this.uploadProgress += 10;
                    }
                }, 200);
                
                const response = await axios.post('/api/v1/course-modules/import', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                });
                
                clearInterval(progressInterval);
                this.uploadProgress = 100;
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert(response.data.message || 'CSV uploaded successfully!');
                    setTimeout(() => {
                        this.goBack();
                    }, 2000);
                } else {
                    window.s_error(response.data.message || 'Failed to upload CSV');
                }
                
            } catch (error) {
                console.error('Error uploading CSV:', error);
                
                if (error.response && error.response.status === 422) {
                    const validationErrors = error.response.data.errors || {};
                    let errorMessage = 'Validation errors:\n';
                    Object.keys(validationErrors).forEach(key => {
                        errorMessage += `${key}: ${validationErrors[key][0]}\n`;
                    });
                    window.s_error(errorMessage);
                } else {
                    window.s_error('Failed to upload CSV file');
                }
            } finally {
                this.uploading = false;
                setTimeout(() => {
                    this.uploadProgress = 0;
                }, 2000);
            }
        },
        
        downloadTemplate() {
            // Generate CSV template
            const csvContent = `milestone_title,milestone_no,module_title,module_no,class_title,class_no,class_type,class_video_link,class_date,class_time,class_topic
"Introduction to Course",1,"Basic Concepts",1,"Welcome Class",1,"live","https://youtube.com/watch?v=example","2024-01-15","09:00","Course Overview"
"Introduction to Course",1,"Basic Concepts",1,"Setup Tutorial",2,"recorded","https://youtube.com/watch?v=example2","2024-01-16","10:00","Environment Setup"
"Advanced Topics",2,"Deep Dive",2,"Advanced Techniques",3,"live","https://youtube.com/watch?v=example3","2024-01-20","11:00","Advanced Concepts"`;
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'course_modules_template.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },
        
        goBack() {
            this.$router.push({ name: 'CourseModule', params: { id: this.$route.params.id } });
        }
    }
};
</script>

<style scoped>
.csv-upload-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem 1.25rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.text-muted {
    color: #6b7280 !important;
    font-size: 0.875rem;
}

.form-actions {
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
    margin-top: 2rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
}

.btn-secondary:hover {
    background-color: #4b5563;
    border-color: #4b5563;
}

.btn-outline-primary {
    color: #3b82f6;
    border-color: #3b82f6;
}

.btn-outline-primary:hover {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.progress {
    height: 1rem;
    background-color: #e5e7eb;
    border-radius: 0.5rem;
}

.progress-bar {
    background-color: #3b82f6;
}

.alert {
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    border: 1px solid transparent;
}

.alert-info {
    color: #0f5132;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffecb5;
}

@media (max-width: 768px) {
    .csv-upload-container {
        padding: 10px;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
