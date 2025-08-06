<template>
    <div class="course-why-learn-form">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i :class="isEditMode ? 'fas fa-edit mr-2' : 'fas fa-plus mr-2'"></i>
                    {{ isEditMode ? 'Edit Faq Item' : 'Create New Faq Item' }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <!-- Title -->
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input 
                            type="text" 
                            id="title"
                            v-model="formData.title"
                            class="form-control"
                            :class="{ 'is-invalid': errors.title }"
                            placeholder="Enter Faq reason"
                            required
                        >
                        <div v-if="errors.title" class="invalid-feedback">
                            {{ errors.title }}
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea 
                            id="description"
                            v-model="formData.description"
                            class="form-control"
                            rows="3"
                            placeholder="Enter Faq description (optional)"
                        ></textarea>
                        <div v-if="errors.description" class="invalid-feedback">
                            {{ errors.description }}
                        </div>
                    </div>
                  
                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
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
                            {{ errors.status }}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <button 
                                type="button" 
                                @click="goBack" 
                                class="btn btn-secondary"
                                :disabled="submitting"
                            >
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back
                            </button>
                            
                            <button 
                                type="submit" 
                                class="btn btn-primary"
                                :disabled="submitting"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else :class="isEditMode ? 'fas fa-save mr-1' : 'fas fa-plus mr-1'"></i>
                                {{ submitting ? 'Processing...' : (isEditMode ? 'Update Item' : 'Create Item') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseFaqForm',
    
    data() {
        return {
            submitting: false,
            loading: false,
            errors: {},
            itemData: null,
            formData: {
                title: '',
                description: '',
                status: 'active',
            },
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
        
        isEditMode() {
            return this.$route.name === 'CourseFaqEdit' && this.$route.params.slug;
        },
        
        courseId() {
            return this.currentCourse?.id;
        },
        
        itemSlug() {
            return this.$route.params.slug;
        }
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async loadItemData() {
            if (!this.isEditMode || !this.itemSlug) return;
            
            this.loading = true;
            try {
                const itemSlug = this.itemSlug;
                console.log('Loading Faq item data for slug:', itemSlug);
                
                const response = await axios.get(`course-faqs/${itemSlug}`);
                
                console.log('Form - API Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    this.itemData = response.data.data;
                    this.populateForm();
                } else {
                    window.s_error('Failed to load Faq item data');
                }
            } catch (error) {
                console.error('Error loading Faq item data:', error);
                window.s_error('Failed to load Faq item data');
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
                
                console.log('Submitting Faq item data:', itemData);
                
                let response;
                if (this.isEditMode) {
                    const itemSlug = this.itemSlug;
                    response = await axios.post(`course-faqs/update/${itemSlug}`, itemData);
                } else {
                    response = await axios.post('course-faqs/store', itemData);
                }
                
                console.log('Form - Submit Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    const message = this.isEditMode ? 'Faq item updated successfully!' : 'Faq item created successfully!';
                    window.s_alert(message);
                    this.goBack();
                } else {
                    window.s_error('Something went wrong while saving the item.');
                }
                
            } catch (error) {
                console.error('Error submitting form:', error);
                
                if (error.response && error.response.status === 422) {
                    // Handle validation errors
                    const validationErrors = error.response.data.errors || {};
                    Object.keys(validationErrors).forEach(key => {
                        this.errors[key] = Array.isArray(validationErrors[key]) ? validationErrors[key][0] : validationErrors[key];
                    });
                    window.s_warning('Please correct the validation errors.');
                } else {
                    window.s_error('Something went wrong. Please try again.');
                }
            } finally {
                this.submitting = false;
            }
        },
        
        goBack() {
            this.$router.push({ name: 'CourseFaq' });
        },
        
        async loadCourseData() {
            const courseId = this.$route.params.id;
            if (courseId) {
                await this.getCourseDetails(courseId);
            }
        }
    },
    
    async mounted() {
        console.log('CourseFaqForm mounted');
        console.log('Route params:', this.$route.params);
        console.log('Is edit mode:', this.isEditMode);
        
        await this.loadCourseData();
        await this.loadItemData();
    },
};
</script>

<style scoped>
.course-why-learn-form {
    position: relative;
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

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: 0.5rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
