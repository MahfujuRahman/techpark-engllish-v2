<template>
    <div class="course-overview">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-eye mr-2"></i>
                    Course Overview
                </h5>
            </div>
            <div class="card-body">
                <!-- Course Overview Form -->
                <form @submit.prevent="saveOverview" v-if="currentCourse">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-7">
                            <!-- Course Title -->
                            <div class="form-group">
                                <label for="title" class="form-label">Course Title</label>
                                <input 
                                    type="text" 
                                    id="title"
                                    v-model="formData.title"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.title }"
                                    placeholder="Enter course title"
                                    @input="generateSlug"
                                >
                                <div v-if="errors.title" class="invalid-feedback">
                                    {{ errors.title[0] }}
                                </div>
                            </div>

                            <!-- What is this Course -->
                            <div class="form-group">
                                <label for="what_is_this_course" class="form-label">What is this Course</label>
                                <div class="text-editor-wrapper" :class="{ 'is-invalid': errors.what_is_this_course }">
                                    <text-editor name="what_is_this_course" />
                                </div>
                                <div v-if="errors.what_is_this_course" class="invalid-feedback d-block">
                                    {{ errors.what_is_this_course[0] }}
                                </div>
                            </div>

                            

                            <!-- Why this Course -->
                            <div class="form-group">
                                <label for="why_is_this_course" class="form-label">Why this Course</label>
                                <div class="text-editor-wrapper" :class="{ 'is-invalid': errors.why_is_this_course }">
                                    <text-editor name="why_is_this_course" />
                                </div>
                                <div v-if="errors.why_is_this_course" class="invalid-feedback d-block">
                                    {{ errors.why_is_this_course[0] }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-5">
                            <!-- Course Category -->
                            <course-category-drop-down-el 
                                :name="'course_category_id'" 
                                :module_name="''"
                                :multiple="false" 
                                :value="formData.course_category_id" 
                            />

                            <!-- Course Image -->
                            <div class="form-group">
                                <label for="image" class="form-label">Course Image</label>
                                <div class="image-upload-container">
                                    <div v-if="imagePreview" class="current-image">
                                        <img 
                                            :src="imagePreview" 
                                            alt="Course Image"
                                            class="img-fluid rounded"
                                        >
                                        <button 
                                            type="button" 
                                            @click="removeImage"
                                            class="btn btn-sm btn-danger remove-image-btn"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div v-else class="upload-placeholder" @click="$refs.imageInput.click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <p>Upload Image</p>
                                        <small>JPG, PNG, GIF (Max 2MB)</small>
                                    </div>
                                    <input 
                                        ref="imageInput"
                                        type="file" 
                                        id="image"
                                        @change="handleImageUpload"
                                        class="d-none"
                                        accept="image/*"
                                    >
                                </div>
                                <div v-if="errors.image" class="invalid-feedback d-block">
                                    {{ errors.image[0] }}
                                </div>
                            </div>

                            <!-- Intro Video -->
                            <div class="form-group">
                                <label for="intro_video" class="form-label">Intro Video URL</label>
                                <input 
                                    type="url" 
                                    id="intro_video" 
                                    v-model="formData.intro_video" 
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.intro_video }"
                                    placeholder="https://youtube.com/watch?v=..."
                                >
                                <div v-if="errors.intro_video" class="invalid-feedback">
                                    {{ errors.intro_video[0] }}
                                </div>
                            </div>

                            <!-- Published Date -->
                            <div class="form-group">
                                <label for="published_at" class="form-label">Published Date</label>
                                <input 
                                    type="date" 
                                    id="published_at" 
                                    v-model="formData.published_at"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.published_at }"
                                >
                                <div v-if="errors.published_at" class="invalid-feedback">
                                    {{ errors.published_at[0] }}
                                </div>
                            </div>

                            <!-- Is Published -->
                            <div class="form-group">
                                <label class="form-label">Publication Status</label>
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        id="is_published" 
                                        v-model="formData.is_published"
                                        class="form-check-input" 
                                        :true-value="1" 
                                        :false-value="0"
                                    >
                                    <label for="is_published" class="form-check-label">
                                        Publish this course
                                    </label>
                                </div>
                                <div v-if="errors.is_published" class="invalid-feedback d-block">
                                    {{ errors.is_published[0] }}
                                </div>
                            </div>

                            <!-- Course Status -->
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
                                    {{ errors.status[0] }}
                                </div>
                            </div>

                            <!-- SEO Section -->
                            <div class="seo-section">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input 
                                        type="text" 
                                        id="meta_title" 
                                        v-model="formData.meta_title"
                                        class="form-control" 
                                        :class="{ 'is-invalid': errors.meta_title }"
                                        placeholder="SEO Meta Title"
                                    >
                                    <div v-if="errors.meta_title" class="invalid-feedback">
                                        {{ errors.meta_title[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea 
                                        id="meta_description" 
                                        v-model="formData.meta_description"
                                        class="form-control" 
                                        :class="{ 'is-invalid': errors.meta_description }"
                                        rows="2" 
                                        placeholder="SEO Meta Description"
                                    ></textarea>
                                    <div v-if="errors.meta_description" class="invalid-feedback">
                                        {{ errors.meta_description[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input 
                                        type="text" 
                                        id="meta_keywords" 
                                        v-model="formData.meta_keywords"
                                        class="form-control" 
                                        :class="{ 'is-invalid': errors.meta_keywords }"
                                        placeholder="keyword1, keyword2, keyword3"
                                    >
                                    <small class="form-text text-muted">Separate keywords with commas</small>
                                    <div v-if="errors.meta_keywords" class="invalid-feedback">
                                        {{ errors.meta_keywords[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            :disabled="submitting"
                        >
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            <i v-else class="fas fa-save mr-1"></i>
                            {{ submitting ? 'Updating...' : 'Update Course' }}
                        </button>

                    </div>
                </form>

                <!-- Loading State -->
                <div v-else class="loading-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">লোড হচ্ছে...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';
import TextEditor from '../../../../../../../GlobalComponents/FormComponents/TextEditor.vue';
import CourseCategoryDropDownEl from '../../../../../../../GlobalManagement/CourseManagement/CourseCategory/components/dropdown/DropDownEl.vue';

export default {
    name: 'CourseOverview',
    
    components: {
        TextEditor,
        CourseCategoryDropDownEl
    },
    
    data() {
        return {
            editor: null,
            imagePreview: null,
            baseUrl: window.location.origin,
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, [
            'currentCourse', 
            'formData', 
            'loading', 
            'submitting', 
            'errors'
        ]),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, [
            'updateCourse', 
            'populateFormWithCourse', 
            'setFormData', 
            'clearErrors',
            'generateSlug'
        ]),
        
        generateSlug() {
            if (this.formData.title) {
                const slug = this.generateSlug(this.formData.title);
                this.setFormData({ slug });
            }
        },
        
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    if (typeof window.s_alert === 'function') {
                        window.s_alert('Image size must be less than 2MB', 'error');
                    } else if (this.$toast) {
                        this.$toast.error('Image size must be less than 2MB');
                    } else {
                        alert('Image size must be less than 2MB');
                    }
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    if (typeof window.s_alert === 'function') {
                        window.s_alert('Only image files are allowed', 'error');
                    } else if (this.$toast) {
                        this.$toast.error('Only image files are allowed');
                    } else {
                        alert('Only image files are allowed');
                    }
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                    console.log('New image preview set from file upload');
                };
                reader.onerror = () => {
                    console.error('Error reading file');
                    if (typeof window.s_alert === 'function') {
                        window.s_alert('Error reading image file', 'error');
                    }
                };
                reader.readAsDataURL(file);
                
                // Store file in form data
                this.setFormData({ image: file });
            }
        },
        
        removeImage() {
            this.imagePreview = null;
            this.setFormData({ image: null });
            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = '';
            }
            
            // Also clear the current course image reference if we're removing it
            if (this.currentCourse && this.currentCourse.image) {
                console.log('Image removed, clearing preview');
            }
        },
        
        async saveOverview() {
            this.clearErrors();
            
            try {
                const formData = new FormData();
                
                // Add text fields (excluding rich text fields first)
                Object.keys(this.formData).forEach(key => {
                    if (key !== 'image' && key !== 'what_is_this_course' && key !== 'why_is_this_course') {
                        let value = this.formData[key];

                        // Handle checkbox values properly
                        if (key === 'is_published') {
                            value = value ? '1' : '0';
                        } else if (value === null || value === undefined) {
                            value = '';
                        }

                        formData.append(key, value);
                    }
                });

                // Add image file if selected (don't send if null to preserve existing image)
                if (this.formData.image) {
                    formData.append('image', this.formData.image);
                }

                // Get course_category_id from the hidden input created by dropdown
                const categoryInput = document.getElementById('course_category_id');
                if (categoryInput && categoryInput.value) {
                    formData.set('course_category_id', categoryInput.value);
                }

                // Add content from Summernote editors (always send, even if empty)
                const whatCourseEditor = $('#what_is_this_course');
                if (whatCourseEditor.length && whatCourseEditor.summernote) {
                    const whatCourseContent = whatCourseEditor.summernote('code');
                    console.log('What is this course content:', whatCourseContent);
                    formData.append('what_is_this_course', whatCourseContent || '');
                } else {
                    // Fallback: try to get content directly from the div
                    const fallbackContent = whatCourseEditor.html() || '';
                    console.log('What is this course fallback content:', fallbackContent);
                    formData.append('what_is_this_course', fallbackContent);
                }

                const whyCourseEditor = $('#why_is_this_course');
                if (whyCourseEditor.length && whyCourseEditor.summernote) {
                    const whyCourseContent = whyCourseEditor.summernote('code');
                    console.log('Why this course content:', whyCourseContent);
                    formData.append('why_is_this_course', whyCourseContent || '');
                } else {
                    // Fallback: try to get content directly from the div
                    const fallbackContent = whyCourseEditor.html() || '';
                    console.log('Why this course fallback content:', fallbackContent);
                    formData.append('why_is_this_course', fallbackContent);
                }

                // Debug: Log all form data entries
                console.log('=== FormData Debug ===');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }
                console.log('======================');

                const response = await this.updateCourse(this.currentCourse.slug, formData);
                
                console.log('API Response:', response);
                console.log('Response status:', response.status);
                console.log('Response statusCode:', response.statusCode);
                console.log('Response message:', response.message);

                // Check statusCode specifically (it's a number), not status (which is a string)
                if ([200, 201].includes(response.statusCode)) {
                    // Show success message
                    const message = response.message || 'Course overview successfully updated';
                    console.log('Showing success message:', message);
                    
                    // Try both window.s_alert and regular alert for debugging
                    if (typeof window.s_alert === 'function') {
                        window.s_alert(message, 'success');
                    } else {
                        console.error('window.s_alert is not available');
                        this.$toast.success(message); // Fallback to toast
                    }
                } else {
                    console.log('Unexpected response statusCode:', response.statusCode);
                    // Handle unexpected response
                    if (typeof window.s_alert === 'function') {
                        window.s_alert('Unexpected response from server', 'error');
                    } else {
                        this.$toast.error('Unexpected response from server');
                    }
                }
                
            } catch (error) {
                if (error.response?.status === 422) {
                    if (typeof window.s_alert === 'function') {
                        window.s_alert('Fill the input fields.', 'error');
                    } else {
                        this.$toast.error('Fill the input fields.');
                    }
                }
                console.error('Error saving course overview:', error);
            }
        },
        
        setImagePreview() {
            if (this.currentCourse && this.currentCourse.image) {
                console.log('Setting image preview for course image:', this.currentCourse.image);
                
                // Check if it's a full URL or relative path
                if (this.currentCourse.image.startsWith('http')) {
                    this.imagePreview = this.currentCourse.image;
                } else if (this.currentCourse.image.startsWith('/')) {
                    // Already has /storage/ prefix
                    this.imagePreview = `${window.location.origin}${this.currentCourse.image}`;
                } else {
                    // Construct full URL for relative paths
                    this.imagePreview = `${window.location.origin}/${this.currentCourse.image}`;
                }
                
                console.log('Image preview set to:', this.imagePreview);
            } else {
                this.imagePreview = null;
                console.log('No image found, imagePreview set to null');
            }
        },
        
        initializeRichTextEditors() {
            // Initialize rich text editors with current course content
            if (this.currentCourse) {
                console.log('Initializing rich text editors with course data');
                
                // Set what_is_this_course content
                const whatEditor = $('#what_is_this_course');
                if (whatEditor.length) {
                    if (whatEditor.summernote) {
                        whatEditor.summernote('code', this.currentCourse.what_is_this_course || '');
                        console.log('Set what_is_this_course content:', this.currentCourse.what_is_this_course);
                    } else {
                        console.warn('Summernote not available for what_is_this_course');
                    }
                }
                
                // Set why_is_this_course content
                const whyEditor = $('#why_is_this_course');
                if (whyEditor.length) {
                    if (whyEditor.summernote) {
                        whyEditor.summernote('code', this.currentCourse.why_is_this_course || '');
                        console.log('Set why_is_this_course content:', this.currentCourse.why_is_this_course);
                    } else {
                        console.warn('Summernote not available for why_is_this_course');
                    }
                }
            }
        },
        
    },
    
    async mounted() {
        console.log('CourseOverview mounted, currentCourse:', this.currentCourse);
        
        if (this.currentCourse) {
            this.populateFormWithCourse(this.currentCourse);
            this.setImagePreview();
        }
        
        // Initialize rich text editors with a slight delay
        this.$nextTick(() => {
            setTimeout(() => {
                this.initializeRichTextEditors();
            }, 100);
        });
    },
    
    watch: {
        currentCourse: {
            handler(newCourse) {
                if (newCourse) {
                    console.log('Course data changed:', newCourse);
                    this.populateFormWithCourse(newCourse);
                    this.setImagePreview();
                    
                    // Wait for next tick to ensure editors are initialized
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.initializeRichTextEditors();
                        }, 100);
                    });
                }
            },
            immediate: true
        }
    }
};
</script>

<style scoped>
.course-overview {
    max-width: 100%;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.image-upload-container {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.current-image {
    position: relative;
}

.current-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

:deep(.card-header) {
    display: block !important;
}

.remove-image-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    cursor: pointer;
    color: #6c757d;
    transition: all 0.3s ease;
}

.upload-placeholder:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.upload-placeholder i {
    margin-bottom: 10px;
    opacity: 0.5;
}

.upload-placeholder p {
    margin: 5px 0;
    font-weight: 500;
}

.upload-placeholder small {
    font-size: 0.8rem;
    opacity: 0.7;
}

.seo-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.seo-section h6 {
    color: #495057;
    margin-bottom: 15px;
}

/* Summernote Editor Styling */
.text-editor-wrapper {
    margin-top: 8px;
    margin-bottom: 12px;
}

/* Simple Summernote styling - matching CreateCourse.vue */
:deep(.note-editor) {
    border-radius: 0.25rem;
}

:deep(.note-editor.note-frame .note-editing-area .note-editable) {
    min-height: 150px;
}

/* Error state for Summernote */
.text-editor-wrapper.is-invalid :deep(.note-editor) {
    border-color: #dc3545;
}

.text-editor-wrapper.is-invalid :deep(.note-editor.note-frame) {
    border-color: #dc3545;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 300px;
}

.input-group-append .form-control {
    border-left: 0;
    border-radius: 0 0.25rem 0.25rem 0;
}

.form-check {
    margin-top: 8px;
}

.form-check-label {
    font-weight: normal;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-5 {
        margin-top: 20px;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
}
</style>
