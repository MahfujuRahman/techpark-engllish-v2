<template>
    <div class="course-banner">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image mr-2"></i>
                    কোর্স ব্যানার ম্যানেজমেন্ট
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveBanner">
                    <div class="row">
                        <!-- Left Column - Banner Image -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="banner_image" class="form-label">ব্যানার ইমেজ</label>
                                <div class="banner-upload-container">
                                    <div v-if="bannerPreview || currentCourse.banner_image" class="current-banner">
                                        <img 
                                            :src="bannerPreview || `/${currentCourse.banner_image}`" 
                                            alt="Course Banner"
                                            class="img-fluid rounded"
                                        >
                                        <button 
                                            type="button" 
                                            @click="removeBanner"
                                            class="btn btn-sm btn-danger remove-banner-btn"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div v-else class="upload-placeholder" @click="$refs.bannerInput.click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <p>ব্যানার ইমেজ আপলোড করুন</p>
                                        <small>JPG, PNG, GIF (Max 5MB)</small>
                                        <small class="text-info">প্রস্তাবিত সাইজ: 1920x1080px</small>
                                    </div>
                                    <input 
                                        ref="bannerInput"
                                        type="file" 
                                        id="banner_image"
                                        @change="handleBannerUpload"
                                        class="d-none"
                                        accept="image/*"
                                    >
                                </div>
                                <div v-if="errors.banner_image" class="invalid-feedback d-block">
                                    {{ errors.banner_image[0] }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Banner Details -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="banner_title" class="form-label">ব্যানার শিরোনাম</label>
                                <input 
                                    type="text" 
                                    id="banner_title"
                                    v-model="formData.banner_title"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.banner_title }"
                                    placeholder="ব্যানারে প্রদর্শিত শিরোনাম"
                                >
                                <div v-if="errors.banner_title" class="invalid-feedback">
                                    {{ errors.banner_title[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="banner_subtitle" class="form-label">ব্যানার সাবটাইটেল</label>
                                <input 
                                    type="text" 
                                    id="banner_subtitle"
                                    v-model="formData.banner_subtitle"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.banner_subtitle }"
                                    placeholder="ব্যানারে প্রদর্শিত সাবটাইটেল"
                                >
                                <div v-if="errors.banner_subtitle" class="invalid-feedback">
                                    {{ errors.banner_subtitle[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="banner_description" class="form-label">ব্যানার বিবরণ</label>
                                <textarea 
                                    id="banner_description"
                                    v-model="formData.banner_description"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.banner_description }"
                                    rows="4"
                                    placeholder="ব্যানারে প্রদর্শিত বিবরণ"
                                ></textarea>
                                <div v-if="errors.banner_description" class="invalid-feedback">
                                    {{ errors.banner_description[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="banner_button_text" class="form-label">ব্যানার বাটন টেক্সট</label>
                                <input 
                                    type="text" 
                                    id="banner_button_text"
                                    v-model="formData.banner_button_text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.banner_button_text }"
                                    placeholder="যেমন: 'এখনই ভর্তি হন'"
                                >
                                <div v-if="errors.banner_button_text" class="invalid-feedback">
                                    {{ errors.banner_button_text[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="banner_button_link" class="form-label">ব্যানার বাটন লিংক</label>
                                <input 
                                    type="url" 
                                    id="banner_button_link"
                                    v-model="formData.banner_button_link"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.banner_button_link }"
                                    placeholder="https://example.com/enroll"
                                >
                                <div v-if="errors.banner_button_link" class="invalid-feedback">
                                    {{ errors.banner_button_link[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        id="banner_show_button"
                                        v-model="formData.banner_show_button"
                                        class="form-check-input"
                                    >
                                    <label for="banner_show_button" class="form-check-label">
                                        ব্যানারে বাটন প্রদর্শন করুন
                                    </label>
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
                            {{ submitting ? 'সংরক্ষণ হচ্ছে...' : 'ব্যানার সংরক্ষণ করুন' }}
                        </button>
                        
                        <button 
                            type="button" 
                            @click="resetBanner"
                            class="btn btn-secondary ml-2"
                        >
                            <i class="fas fa-undo mr-1"></i>
                            রিসেট করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Banner Preview -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-eye mr-1"></i>
                    ব্যানার প্রিভিউ
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="banner-preview">
                    <div 
                        class="banner-content"
                        :style="bannerStyle"
                    >
                        <div class="banner-overlay">
                            <div class="container">
                                <div class="banner-text">
                                    <h1 v-if="formData.banner_title" class="banner-title">
                                        {{ formData.banner_title }}
                                    </h1>
                                    <h2 v-if="formData.banner_subtitle" class="banner-subtitle">
                                        {{ formData.banner_subtitle }}
                                    </h2>
                                    <p v-if="formData.banner_description" class="banner-description">
                                        {{ formData.banner_description }}
                                    </p>
                                    <div v-if="formData.banner_show_button && formData.banner_button_text" class="banner-button-container">
                                        <a 
                                            :href="formData.banner_button_link || '#'"
                                            class="btn btn-primary btn-lg banner-button"
                                            target="_blank"
                                        >
                                            {{ formData.banner_button_text }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No Content Message -->
                    <div v-if="!hasAnyBannerContent" class="no-preview">
                        <i class="fas fa-image fa-3x"></i>
                        <p>ব্যানার তথ্য যোগ করুন প্রিভিউ দেখতে</p>
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
    name: 'CourseBanner',
    
    data() {
        return {
            bannerPreview: null,
            formData: {
                banner_title: '',
                banner_subtitle: '',
                banner_description: '',
                banner_button_text: '',
                banner_button_link: '',
                banner_show_button: false,
                banner_image: null,
            },
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, [
            'currentCourse', 
            'submitting', 
            'errors'
        ]),
        
        bannerStyle() {
            const imageUrl = this.bannerPreview || (this.currentCourse.banner_image ? `/${this.currentCourse.banner_image}` : '');
            return {
                backgroundImage: imageUrl ? `url(${imageUrl})` : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                backgroundRepeat: 'no-repeat',
            };
        },
        
        hasAnyBannerContent() {
            return this.formData.banner_title || 
                   this.formData.banner_subtitle || 
                   this.formData.banner_description || 
                   this.bannerPreview || 
                   this.currentCourse.banner_image;
        }
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, [
            'updateCourse', 
            'clearErrors'
        ]),
        
        handleBannerUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    this.$toast.error('ব্যানার ইমেজের সাইজ ৫MB এর কম হতে হবে');
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
                    this.bannerPreview = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Store file in form data
                this.formData.banner_image = file;
            }
        },
        
        removeBanner() {
            this.bannerPreview = null;
            this.formData.banner_image = null;
            this.$refs.bannerInput.value = '';
        },
        
        async saveBanner() {
            this.clearErrors();
            
            try {
                const formData = new FormData();
                
                // Add text fields
                Object.keys(this.formData).forEach(key => {
                    if (key !== 'banner_image' && this.formData[key] !== null) {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                // Add banner image file if selected
                if (this.formData.banner_image) {
                    formData.append('banner_image', this.formData.banner_image);
                }
                
                formData.append('_method', 'PUT');
                
                await this.updateCourse(this.currentCourse.id, formData);
                
                this.$toast.success('কোর্স ব্যানার সফলভাবে আপডেট হয়েছে!');
                
            } catch (error) {
                this.$toast.error('কোর্স ব্যানার আপডেট করতে ত্রুটি হয়েছে!');
                console.error('Error saving banner:', error);
            }
        },
        
        resetBanner() {
            if (this.currentCourse) {
                this.formData = {
                    banner_title: this.currentCourse.banner_title || '',
                    banner_subtitle: this.currentCourse.banner_subtitle || '',
                    banner_description: this.currentCourse.banner_description || '',
                    banner_button_text: this.currentCourse.banner_button_text || '',
                    banner_button_link: this.currentCourse.banner_button_link || '',
                    banner_show_button: this.currentCourse.banner_show_button || false,
                    banner_image: null,
                };
                this.bannerPreview = null;
            }
            this.clearErrors();
        },
        
        populateFormData() {
            if (this.currentCourse) {
                this.formData = {
                    banner_title: this.currentCourse.banner_title || '',
                    banner_subtitle: this.currentCourse.banner_subtitle || '',
                    banner_description: this.currentCourse.banner_description || '',
                    banner_button_text: this.currentCourse.banner_button_text || '',
                    banner_button_link: this.currentCourse.banner_button_link || '',
                    banner_show_button: this.currentCourse.banner_show_button || false,
                    banner_image: null,
                };
            }
        }
    },
    
    mounted() {
        if (!this.currentCourse) {
            this.$toast.error('কোর্স তথ্য পাওয়া যায়নি');
            return;
        }
        
        this.populateFormData();
    },
    
    watch: {
        currentCourse: {
            handler(newCourse) {
                if (newCourse) {
                    this.populateFormData();
                }
            },
            immediate: false
        }
    }
};
</script>

<style scoped>
.course-banner {
    max-width: 100%;
}

.banner-upload-container {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.current-banner {
    position: relative;
}

.current-banner img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.remove-banner-btn {
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
    height: 250px;
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
    display: block;
    margin: 2px 0;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.banner-preview {
    min-height: 300px;
    position: relative;
}

.banner-content {
    height: 400px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-text {
    text-align: center;
    color: white;
    max-width: 800px;
    padding: 0 20px;
}

.banner-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.banner-subtitle {
    font-size: 1.5rem;
    font-weight: 500;
    margin-bottom: 1rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.banner-description {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    line-height: 1.6;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.banner-button-container {
    margin-top: 2rem;
}

.banner-button {
    font-size: 1.1rem;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.banner-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    text-decoration: none;
    color: white;
}

.no-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 300px;
    color: #6c757d;
}

.no-preview i {
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-preview p {
    margin: 0;
    font-size: 1.1rem;
}

.form-check {
    margin-top: 8px;
}

.form-check-label {
    font-weight: normal;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

/* Responsive Design */
@media (max-width: 768px) {
    .banner-title {
        font-size: 1.8rem;
    }
    
    .banner-subtitle {
        font-size: 1.2rem;
    }
    
    .banner-description {
        font-size: 1rem;
    }
    
    .banner-content {
        height: 300px;
    }
    
    .upload-placeholder {
        height: 200px;
    }
    
    .current-banner img {
        height: 200px;
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
