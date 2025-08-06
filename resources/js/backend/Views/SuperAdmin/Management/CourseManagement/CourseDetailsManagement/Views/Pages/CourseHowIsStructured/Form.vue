<template>
    <div class="course-how-is-structured-form">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap mr-2"></i>
                        {{ isEditMode ? 'Edit Structure Item' : 'Add New Structure Item' }}
                    </h5>
                    <router-link 
                        :to="{ name: 'CourseHowIsStructuredAll', params: { id: $route.params.id } }"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to List
                    </router-link>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title" class="form-label required">
                                    <i class="fas fa-heading mr-1"></i>
                                    Structure Title
                                </label>
                                <textarea
                                    id="title"
                                    v-model="formData.title"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.title }"
                                    rows="3"
                                    placeholder="Enter structure title (e.g., Module 1: Introduction to Programming)"
                                    required
                                ></textarea>
                                <div v-if="errors.title" class="invalid-feedback">
                                    {{ errors.title[0] || errors.title }}
                                </div>
                                <small class="form-text text-muted">
                                    Describe this part of the course structure clearly and concisely.
                                </small>
                            </div>
                        </div>

                        <!-- Serial Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="serial" class="form-label">
                                    <i class="fas fa-sort-numeric-up mr-1"></i>
                                    Serial Number
                                </label>
                                <input
                                    id="serial"
                                    v-model.number="formData.serial"
                                    type="number"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.serial }"
                                    min="0"
                                    placeholder="0"
                                />
                                <div v-if="errors.serial" class="invalid-feedback">
                                    {{ errors.serial[0] || errors.serial }}
                                </div>
                                <small class="form-text text-muted">
                                    Order of this item in the structure (0 for auto).
                                </small>
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on mr-1"></i>
                                    Status
                                </label>
                                <select
                                    id="status"
                                    v-model="formData.status"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.status }"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div v-if="errors.status" class="invalid-feedback">
                                    {{ errors.status[0] || errors.status }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <router-link 
                                :to="{ name: 'CourseHowIsStructuredAll', params: { id: $route.params.id } }"
                                class="btn btn-outline-secondary"
                            >
                                <i class="fas fa-times mr-1"></i>
                                Cancel
                            </router-link>
                            
                            <button 
                                type="submit" 
                                class="btn btn-primary"
                                :disabled="submitting"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else-if="isEditMode" class="fas fa-save mr-1"></i>
                                <i v-else class="fas fa-plus mr-1"></i>
                                {{ submitting ? 'Saving...' : (isEditMode ? 'Update Item' : 'Create Item') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseHowIsStructuredForm',
    
    data() {
        return {
            submitting: false,
            loading: false,
            errors: {},
            itemData: null,
            formData: {
                title: '',
                serial: 0,
                status: 'active',
            },
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
        
        courseId() {
            return this.currentCourse?.id;
        },
        
        isEditMode() {
            return !!this.$route.params.slug;
        },
        
        itemSlug() {
            return this.$route.params.slug;
        },
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async loadItemData() {
            if (!this.isEditMode) return;
            
            this.loading = true;
            try {
                const itemSlug = this.itemSlug;
                console.log('Loading structure item data for slug:', itemSlug);
                
                const response = await axios.get(`course-how-is-structureds/${itemSlug}`);
                console.log('Item data response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    this.itemData = response.data.data;
                    this.populateForm();
                } else {
                    window.s_error('Failed to load structure item data');
                    this.goBack();
                }
            } catch (error) {
                console.error('Error loading structure item data:', error);
                window.s_error('Failed to load structure item data');
                this.goBack();
            } finally {
                this.loading = false;
            }
        },
        
        populateForm() {
            if (!this.itemData) return;
            
            // Populate form with existing data
            Object.keys(this.formData).forEach(key => {
                if (this.itemData[key] !== undefined && this.itemData[key] !== null) {
                    this.formData[key] = this.itemData[key];
                }
            });
        },
        
        validateForm() {
            this.errors = {};
            
            if (!this.formData.title?.trim()) {
                this.errors.title = 'Title is required';
            }
            
            return Object.keys(this.errors).length === 0;
        },
        
        async submitForm() {
            if (!this.validateForm()) {
                window.s_warning('Please fill all required fields correctly.');
                return;
            }
            
            if (!this.courseId) {
                window.s_error('Course ID not found.');
                return;
            }
            
            this.submitting = true;
            
            try {
                const itemData = {
                    ...this.formData,
                    course_id: this.courseId,
                };
                
                // Remove empty values
                Object.keys(itemData).forEach(key => {
                    if (itemData[key] === '' || itemData[key] === null) {
                        delete itemData[key];
                    }
                });
                
                console.log('Submitting structure item data:', itemData);
                
                let response;
                if (this.isEditMode) {
                    const itemSlug = this.itemSlug;
                    response = await axios.post(`course-how-is-structureds/update/${itemSlug}`, itemData);
                } else {
                    response = await axios.post('course-how-is-structureds/store', itemData);
                }
                
                console.log('Submit - API Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditMode ? 'Structure item updated successfully!' : 'Structure item created successfully!');
                    this.goBack();
                } else {
                    window.s_error(response.data.message || 'Failed to save structure item');
                }
                
            } catch (error) {
                console.error('Error saving structure item:', error);
                
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    window.s_warning('Form has errors. Please correct them.');
                } else {
                    window.s_error('Failed to save structure item');
                }
            } finally {
                this.submitting = false;
            }
        },
        
        goBack() {
            this.$router.push({ 
                name: 'CourseHowIsStructuredAll', 
                params: { id: this.$route.params.id } 
            });
        },
    },
    
    async mounted() {
        // Ensure we have course details
        const courseSlug = this.$route.params.id;
        if (courseSlug && !this.currentCourse) {
            await this.getCourseDetails(courseSlug);
        }
        
        // Load item data if in edit mode
        if (this.isEditMode) {
            await this.loadItemData();
        }
    }
};
</script>

<style scoped>
.course-how-is-structured-form {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    padding: 20px;
}

.card-title {
    color: #fff;
    font-weight: 600;
}

.card-body {
    padding: 30px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-label.required::after {
    content: ' *';
    color: #e74c3c;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.form-control.is-invalid {
    border-color: #e74c3c;
}

.invalid-feedback {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 4px;
}

.form-text {
    font-size: 12px;
    margin-top: 4px;
}

.course-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 0;
}

.alert {
    border-radius: 8px;
    border: none;
    margin-bottom: 0;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left: 4px solid #17a2b8;
}

.alert-heading {
    color: #0c5460;
    margin-bottom: 10px;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 10px 20px;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border-color: #fff;
    color: #fff;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .card-body {
        padding: 20px;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
