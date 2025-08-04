<template>
    <div class="course-overview">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-eye mr-2"></i>
                    কোর্স ওভারভিউ
                </h5>
            </div>
            <div class="card-body">
                <!-- Course Overview Form -->
                <form @submit.prevent="saveOverview" v-if="currentCourse">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Course Title -->
                            <div class="form-group">
                                <label for="title" class="form-label required">কোর্সের শিরোনাম</label>
                                <input 
                                    type="text" 
                                    id="title"
                                    v-model="formData.title"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.title }"
                                    placeholder="কোর্সের শিরোনাম লিখুন"
                                    @input="generateSlug"
                                    required
                                >
                                <div v-if="errors.title" class="invalid-feedback">
                                    {{ errors.title[0] }}
                                </div>
                            </div>

                            <!-- Course Slug -->
                            <div class="form-group">
                                <label for="slug" class="form-label">স্লাগ</label>
                                <input 
                                    type="text" 
                                    id="slug"
                                    v-model="formData.slug"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.slug }"
                                    placeholder="course-slug"
                                    readonly
                                >
                                <small class="form-text text-muted">
                                    URL: {{ baseUrl }}/course/{{ formData.slug || 'course-slug' }}
                                </small>
                                <div v-if="errors.slug" class="invalid-feedback">
                                    {{ errors.slug[0] }}
                                </div>
                            </div>

                            <!-- Course Short Description -->
                            <div class="form-group">
                                <label for="short_description" class="form-label">সংক্ষিপ্ত বিবরণ</label>
                                <textarea 
                                    id="short_description"
                                    v-model="formData.short_description"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.short_description }"
                                    rows="3"
                                    placeholder="কোর্সের সংক্ষিপ্ত বিবরণ লিখুন..."
                                ></textarea>
                                <div v-if="errors.short_description" class="invalid-feedback">
                                    {{ errors.short_description[0] }}
                                </div>
                            </div>

                            <!-- Course Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">বিস্তারিত বিবরণ</label>
                                <div 
                                    id="description"
                                    ref="editor"
                                    class="ck-editor-container"
                                    :class="{ 'is-invalid': errors.description }"
                                ></div>
                                <div v-if="errors.description" class="invalid-feedback">
                                    {{ errors.description[0] }}
                                </div>
                            </div>

                            <!-- Course Prerequisites -->
                            <div class="form-group">
                                <label for="prerequisites" class="form-label">পূর্বশর্ত</label>
                                <textarea 
                                    id="prerequisites"
                                    v-model="formData.prerequisites"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.prerequisites }"
                                    rows="3"
                                    placeholder="কোর্সের পূর্বশর্ত লিখুন..."
                                ></textarea>
                                <div v-if="errors.prerequisites" class="invalid-feedback">
                                    {{ errors.prerequisites[0] }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Course Image -->
                            <div class="form-group">
                                <label for="image" class="form-label">কোর্সের ছবি</label>
                                <div class="image-upload-container">
                                    <div v-if="imagePreview || currentCourse.image" class="current-image">
                                        <img 
                                            :src="imagePreview || `/${currentCourse.image}`" 
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
                                        <p>ছবি আপলোড করুন</p>
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

                            <!-- Course Type -->
                            <div class="form-group">
                                <label for="type" class="form-label required">কোর্সের ধরন</label>
                                <select 
                                    id="type"
                                    v-model="formData.type"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.type }"
                                    required
                                >
                                    <option value="">কোর্সের ধরন নির্বাচন করুন</option>
                                    <option value="online">অনলাইন</option>
                                    <option value="offline">অফলাইন</option>
                                    <option value="hybrid">হাইব্রিড</option>
                                </select>
                                <div v-if="errors.type" class="invalid-feedback">
                                    {{ errors.type[0] }}
                                </div>
                            </div>

                            <!-- Course Level -->
                            <div class="form-group">
                                <label for="level" class="form-label">কোর্সের স্তর</label>
                                <select 
                                    id="level"
                                    v-model="formData.level"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.level }"
                                >
                                    <option value="beginner">শুরুর স্তর</option>
                                    <option value="intermediate">মধ্যম স্তর</option>
                                    <option value="advanced">উন্নত স্তর</option>
                                </select>
                                <div v-if="errors.level" class="invalid-feedback">
                                    {{ errors.level[0] }}
                                </div>
                            </div>

                            <!-- Course Duration -->
                            <div class="form-group">
                                <label for="duration" class="form-label">কোর্সের মেয়াদ</label>
                                <div class="input-group">
                                    <input 
                                        type="number" 
                                        id="duration"
                                        v-model="formData.duration"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.duration }"
                                        placeholder="৬"
                                        min="1"
                                    >
                                    <div class="input-group-append">
                                        <select 
                                            v-model="formData.duration_type"
                                            class="form-control"
                                        >
                                            <option value="days">দিন</option>
                                            <option value="weeks">সপ্তাহ</option>
                                            <option value="months">মাস</option>
                                            <option value="years">বছর</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="errors.duration" class="invalid-feedback">
                                    {{ errors.duration[0] }}
                                </div>
                            </div>

                            <!-- Course Language -->
                            <div class="form-group">
                                <label for="language" class="form-label">ভাষা</label>
                                <select 
                                    id="language"
                                    v-model="formData.language"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.language }"
                                >
                                    <option value="bengali">বাংলা</option>
                                    <option value="english">ইংরেজি</option>
                                    <option value="both">উভয়</option>
                                </select>
                                <div v-if="errors.language" class="invalid-feedback">
                                    {{ errors.language[0] }}
                                </div>
                            </div>

                            <!-- Certificate -->
                            <div class="form-group">
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        id="certificate"
                                        v-model="formData.certificate"
                                        class="form-check-input"
                                    >
                                    <label for="certificate" class="form-check-label">
                                        সার্টিফিকেট প্রদান করা হবে
                                    </label>
                                </div>
                            </div>

                            <!-- Course Status -->
                            <div class="form-group">
                                <label for="status" class="form-label">স্ট্যাটাস</label>
                                <select 
                                    id="status"
                                    v-model="formData.status"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.status }"
                                >
                                    <option value="active">সক্রিয়</option>
                                    <option value="inactive">নিষ্ক্রিয়</option>
                                    <option value="draft">খসড়া</option>
                                </select>
                                <div v-if="errors.status" class="invalid-feedback">
                                    {{ errors.status[0] }}
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
                            {{ submitting ? 'সংরক্ষণ হচ্ছে...' : 'সংরক্ষণ করুন' }}
                        </button>
                        
                        <button 
                            type="button" 
                            @click="resetForm"
                            class="btn btn-secondary ml-2"
                        >
                            <i class="fas fa-undo mr-1"></i>
                            রিসেট করুন
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

export default {
    name: 'CourseOverview',
    
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
                    this.$toast.error('ছবির সাইজ ২MB এর কম হতে হবে');
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    this.$toast.error('শুধুমাত্র ছবি ফাইল আপলোড করা যাবে');
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Store file in form data
                this.setFormData({ image: file });
            }
        },
        
        removeImage() {
            this.imagePreview = null;
            this.setFormData({ image: null });
            this.$refs.imageInput.value = '';
        },
        
        async saveOverview() {
            this.clearErrors();
            
            try {
                const formData = new FormData();
                
                // Add text fields
                Object.keys(this.formData).forEach(key => {
                    if (key !== 'image' && this.formData[key] !== null) {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                // Add image file if selected
                if (this.formData.image) {
                    formData.append('image', this.formData.image);
                }
                
                // Add description from editor
                if (this.editor) {
                    formData.append('description', this.editor.getData());
                }
                
                await this.updateCourse(this.currentCourse.id, formData);
                
                this.$toast.success('কোর্স ওভারভিউ সফলভাবে আপডেট হয়েছে!');
                
            } catch (error) {
                this.$toast.error('কোর্স ওভারভিউ আপডেট করতে ত্রুটি হয়েছে!');
                console.error('Error saving course overview:', error);
            }
        },
        
        resetForm() {
            if (this.currentCourse) {
                this.populateFormWithCourse(this.currentCourse);
                this.imagePreview = null;
                if (this.editor) {
                    this.editor.setData(this.currentCourse.description || '');
                }
            }
            this.clearErrors();
        },
        
        async initializeCKEditor() {
            try {
                if (window.ClassicEditor) {
                    this.editor = await window.ClassicEditor.create(this.$refs.editor, {
                        placeholder: 'কোর্সের বিস্তারিত বিবরণ লিখুন...',
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
                    
                    // Set initial content
                    if (this.currentCourse && this.currentCourse.description) {
                        this.editor.setData(this.currentCourse.description);
                    }
                }
            } catch (error) {
                console.error('Error initializing CKEditor:', error);
            }
        },
    },
    
    async mounted() {
        if (this.currentCourse) {
            this.populateFormWithCourse(this.currentCourse);
        }
        
        // Initialize CKEditor
        await this.$nextTick();
        await this.initializeCKEditor();
    },
    
    beforeUnmount() {
        if (this.editor) {
            this.editor.destroy();
        }
    },
    
    watch: {
        currentCourse: {
            handler(newCourse) {
                if (newCourse) {
                    this.populateFormWithCourse(newCourse);
                    if (this.editor) {
                        this.editor.setData(newCourse.description || '');
                    }
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
