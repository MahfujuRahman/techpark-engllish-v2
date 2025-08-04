<template>
    <div class="create-course">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus mr-2" v-if="!isEditMode"></i>
                    <i class="fas fa-edit mr-2" v-if="isEditMode"></i>
                    {{ pageTitle }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="createCourse">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-7">
                            <!-- Course Title -->
                            <div class="form-group">
                                <label for="title" class="form-label ">Course Title</label>
                                <input type="text" id="title" v-model="formData.title" class="form-control"
                                    placeholder="Enter course title" @input="generateSlug">
                            </div>

                            <!-- Course Slug -->
                            <div class="form-group">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" id="slug" v-model="formData.slug" class="form-control"
                                    placeholder="course-slug" readonly>
                                <small class="form-text text-muted ">
                                    URL: {{ baseUrl }}/course/{{ formData.slug || 'course-slug' }}
                                </small>
                            </div>

                            <!-- What is this Course -->
                            <div class="form-group">
                                <label for="what_is_this_course" class="form-label">What is this Course</label>
                                <div class="mt-1 mb-3">
                                    <text-editor name="what_is_this_course" />
                                </div>
                            </div>

                            <!-- Why this Course -->
                            <div class="form-group">
                                <label for="why_is_this_course" class="form-label">Why this Course</label>
                                <div class="mt-1 mb-3">
                                    <text-editor name="why_is_this_course" />
                                </div>
                            </div>

                        </div>

                        <!-- Right Column -->
                        <div class="col-md-5">
                            <!-- Course Category -->

                            <course-category-drop-down-el :name="'course_category_id'" :module_name="''"
                                :multiple="false" :value="formData.course_category_id" />

                            <!-- Course Image -->
                            <div class="form-group">
                                <label for="image" class="form-label ">Course Image</label>
                                <div class="image-upload-container">
                                    <div v-if="imagePreview" class="current-image">
                                        <img :src="imagePreview" alt="Course Image" class="img-fluid rounded">
                                        <button type="button" @click="removeImage"
                                            class="btn btn-sm btn-danger remove-image-btn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div v-else class="upload-placeholder" @click="$refs.imageInput.click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <p>Upload Image</p>
                                        <small>JPG, PNG, GIF (Max 2MB)</small>
                                    </div>
                                    <input ref="imageInput" type="file" id="image" @change="handleImageUpload"
                                        class="d-none" accept="image/*">
                                </div>
                            </div>

                            <!-- Intro Video -->
                            <div class="form-group">
                                <label for="intro_video" class="form-label ">Intro Video URL</label>
                                <input type="url" id="intro_video" v-model="formData.intro_video" class="form-control"
                                    placeholder="https://youtube.com/watch?v=...">
                            </div>

                            <!-- Published Date -->
                            <div class="form-group">
                                <label for="published_at" class="form-label ">Published Date</label>
                                <input type="date" id="published_at" v-model="formData.published_at"
                                    class="form-control">
                            </div>

                            <!-- Is Published -->
                            <div class="form-group">
                                <label class="form-label ">Publication Status</label>
                                <div class="form-check">
                                    <input type="checkbox" id="is_published" v-model="formData.is_published"
                                        class="form-check-input" :true-value="1" :false-value="0">
                                    <label for="is_published" class="form-check-label">
                                        Publish this course
                                    </label>
                                </div>
                            </div>

                            <!-- Course Status -->
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" v-model="formData.status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- SEO Section -->
                            <div class="seo-section">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" id="meta_title" v-model="formData.meta_title"
                                        class="form-control" placeholder="SEO Meta Title">
                                </div>

                                <div class="form-group">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea id="meta_description" v-model="formData.meta_description"
                                        class="form-control" rows="2" placeholder="SEO Meta Description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" id="meta_keywords" v-model="formData.meta_keywords"
                                        class="form-control" placeholder="keyword1, keyword2, keyword3">
                                    <small class="form-text text-muted">Separate keywords with commas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" :disabled="submitting">
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            <i v-else class="fas fa-save mr-1"></i>
                            {{ submitButtonText }}
                        </button>

                        <button type="button" @click="resetForm" class="btn btn-secondary ml-2">
                            <i class="fas fa-undo mr-1"></i>
                            Reset
                        </button>

                        <router-link :to="{ name: 'AllCourses' }" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Go Back
                        </router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { useCourseDetailsStore } from '../Store/courseDetailsStore';
import TextEditor from '../../../../../../GlobalComponents/FormComponents/TextEditor.vue';
import CourseCategoryDropDownEl from '../../../../../../GlobalManagement/CourseManagement/CourseCategory/components/dropdown/DropDownEl.vue';

export default {
    name: 'CreateCourse',

    components: {
        TextEditor,
        CourseCategoryDropDownEl
    },

    props: {
        id: {
            type: [String, Number],
            default: null
        }
    },

    setup() {
        const store = useCourseDetailsStore();
        return { store };
    },

    data() {
        return {
            editor: null,
            imagePreview: null,
            baseUrl: window.location.origin,
        };
    },

    computed: {
        isEditMode() {
            return !!this.id;
        },
        pageTitle() {
            return this.isEditMode ? 'Edit Course' : 'Create New Course';
        },
        submitButtonText() {
            if (this.submitting) {
                return this.isEditMode ? 'Updating...' : 'Creating...';
            }
            return this.isEditMode ? 'Update Course' : 'Create Course';
        },
        formData() {
            return this.store.formData;
        },
        submitting() {
            return this.store.submitting;
        },
        errors() {
            return this.store.errors;
        }
    },

    methods: {
        generateSlug() {
            if (this.formData.title) {
                const slug = this.store.generateSlug(this.formData.title);
                this.store.setFormData({ slug });
            }
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);

                // Store file in form data
                this.store.setFormData({ image: file });
            }
        },

        removeImage() {
            this.imagePreview = null;
            this.store.setFormData({ image: null });
            this.$refs.imageInput.value = '';
        },

        async createCourse() {
            this.store.clearErrors();

            try {
                const formData = new FormData();

                // Add text fields
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

                // Add image file if selected
                if (this.formData.image) {
                    formData.append('image', this.formData.image);
                } else {
                    // Send empty for validation
                    formData.append('image', '');
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

                let response;
                let successMessage;

                if (this.isEditMode) {
                    // Update existing course
                    response = await this.store.updateCourse(this.id, formData);
                    successMessage = 'Data successfully updated';
                } else {
                    // Create new course
                    response = await this.store.createCourse(formData);
                    successMessage = 'Data Successfully Created';
                }

                console.log('API Response:', response);
                console.log('Response status:', response.status);
                console.log('Response statusCode:', response.statusCode);
                console.log('Response message:', response.message);

                // Check statusCode specifically (it's a number), not status (which is a string)
                if ([200, 201].includes(response.statusCode)) {
                    // Show success message
                    const message = response.message || successMessage;
                    console.log('Showing success message:', message);
                    
                    // Try both window.s_alert and regular alert for debugging
                    if (typeof window.s_alert === 'function') {
                        window.s_alert(message, 'success');
                    } else {
                        console.error('window.s_alert is not available');
                        alert(`SUCCESS: ${message}`); // Fallback
                    }

                    // Add a small delay before redirect to ensure message is visible
                    setTimeout(() => {
                        // Redirect to course details
                        if (response && response.data && response.data.slug) {
                            console.log('Redirecting to course details with slug:', response.data.slug);
                            this.$router.push({
                                name: 'CourseDetails',
                                params: { id: response.data.slug }
                            });
                        } else {
                            console.log('Redirecting to course management');
                            // Redirect to all courses
                            this.$router.push({ name: 'CourseDetailsManagement' });
                        }
                    }, 2000); // 2 second delay
                } else {
                    console.log('Unexpected response statusCode:', response.statusCode);
                    // Handle unexpected response
                    window.s_alert('Unexpected response from server', 'error');
                }

            } catch (error) {
                if (error.response?.status === 422) {
                    window.s_alert('Fill the input fields.', 'error');
                }
                console.error('Error saving course:', error);
            }
        },

        resetForm() {
            this.store.resetFormData();
            this.imagePreview = null;
            this.$refs.imageInput.value = '';
            if (this.editor) {
                this.editor.setData('');
            }

            // Clear Summernote editors
            if ($('#what_is_this_course').length && $('#what_is_this_course').summernote) {
                $('#what_is_this_course').summernote('code', '');
            }
            if ($('#why_is_this_course').length && $('#why_is_this_course').summernote) {
                $('#why_is_this_course').summernote('code', '');
            }

            this.store.clearErrors();
        },

        async initializeCKEditor() {
            try {
                if (window.ClassicEditor) {
                    // Initialize main description editor
                    this.editor = await window.ClassicEditor.create(this.$refs.editor, {
                        placeholder: 'Write detailed course description...',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                            ]
                        }
                    });
                }
            } catch (error) {
                console.error('Error initializing CKEditor:', error);
            }
        },

        setSummernoteContent(courseData) {
            // Set content for Summernote editors after they are initialized
            setTimeout(() => {
                if (courseData.what_is_this_course && $('#what_is_this_course').length && $('#what_is_this_course').summernote) {
                    $('#what_is_this_course').summernote('code', courseData.what_is_this_course);
                }
                if (courseData.why_is_this_course && $('#why_is_this_course').length && $('#why_is_this_course').summernote) {
                    $('#why_is_this_course').summernote('code', courseData.why_is_this_course);
                }
            }, 2000); // Wait for Summernote to initialize
        }
    },

    async mounted() {
        if (this.isEditMode) {
            // Load course data for editing using slug
            try {
                const response = await this.store.getCourseDetails(this.id);
                if (response && response.data) {
                    this.store.populateFormWithCourse(response.data);

                    // Set editor content after initialization
                    await this.$nextTick();
                    await this.initializeCKEditor();
                    if (this.editor && response.data.description) {
                        this.editor.setData(response.data.description);
                    }

                    // Set Summernote content
                    this.setSummernoteContent(response.data);
                } else {
                    window.s_alert('Error loading course data', 'error');
                }
            } catch (error) {
                window.s_alert('Error loading course data', 'error');
                console.error('Error loading course:', error);
            }
        } else {
            // Reset form data for new course
            this.store.resetFormData();

            // Initialize CKEditor
            await this.$nextTick();
            await this.initializeCKEditor();
        }
    },

    beforeUnmount() {
        if (this.editor) {
            this.editor.destroy();
        }

        // Clean up Summernote instances
        if ($('#what_is_this_course').length && $('#what_is_this_course').summernote) {
            $('#what_is_this_course').summernote('destroy');
        }
        if ($('#why_is_this_course').length && $('#why_is_this_course').summernote) {
            $('#why_is_this_course').summernote('destroy');
        }
    }
};
</script>

<style scoped>
.create-course {
    max-width: 100%;
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

.ck-editor-container {
    min-height: 200px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.ck-editor-container.is-invalid {
    border-color: #dc3545;
}

/* Summernote styling */
.note-editor {
    border-radius: 0.25rem;
}

.note-editor.note-frame .note-editing-area .note-editable {
    min-height: 150px;
}

.seo-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.input-group-append .form-control {
    border-left: 0;
    border-radius: 0 0.25rem 0.25rem 0;
}

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
}

.form-check {
    margin-top: 8px;
}

.form-check-label {
    font-weight: normal;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-4 {
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
