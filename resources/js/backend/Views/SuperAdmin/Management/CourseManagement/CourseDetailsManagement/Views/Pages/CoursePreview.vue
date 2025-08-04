<template>
    <div class="course-preview">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye mr-2"></i>
                        কোর্স প্রিভিউ
                    </h5>
                    <div class="header-actions">
                        <button 
                            @click="refreshPreview"
                            class="btn btn-sm btn-outline-primary"
                            :disabled="loading"
                        >
                            <i class="fas fa-sync-alt mr-1" :class="{ 'fa-spin': loading }"></i>
                            রিফ্রেশ
                        </button>
                        <button 
                            @click="exportPreview"
                            class="btn btn-sm btn-success ml-2"
                        >
                            <i class="fas fa-download mr-1"></i>
                            এক্সপোর্ট
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">লোড হচ্ছে...</span>
            </div>
            <p class="mt-2 text-muted">কোর্স প্রিভিউ লোড হচ্ছে...</p>
        </div>

        <div v-else-if="currentCourse" class="course-preview-content">
            <!-- Course Header -->
            <div class="preview-section course-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="course-title">{{ currentCourse.title }}</h1>
                        <p class="course-subtitle">{{ currentCourse.subtitle }}</p>
                        
                        <div class="course-meta">
                            <span class="meta-item">
                                <i class="fas fa-tag text-primary"></i>
                                {{ getCategoryName(currentCourse.category_id) }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock text-success"></i>
                                {{ currentCourse.duration || 'সময় নির্ধারিত নয়' }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-signal text-warning"></i>
                                {{ getDifficultyLabel(currentCourse.difficulty_level) }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-money-bill-wave text-info"></i>
                                ৳{{ formatPrice(currentCourse.price) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div v-if="currentCourse.thumbnail" class="course-thumbnail">
                            <img 
                                :src="getThumbnailUrl(currentCourse.thumbnail)" 
                                :alt="currentCourse.title"
                                class="img-fluid rounded"
                            >
                        </div>
                        <div v-else class="no-thumbnail">
                            <i class="fas fa-image text-muted"></i>
                            <p class="text-muted">কোন ছবি নেই</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div v-if="currentCourse.description" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    কোর্স বিবরণ
                </h3>
                <div class="content-preview" v-html="currentCourse.description"></div>
            </div>

            <!-- Course Statistics -->
            <div class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-chart-bar mr-2"></i>
                    কোর্স পরিসংখ্যান
                </h3>
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ currentCourse.max_students || '৫০' }}</h4>
                                <p>সর্বোচ্চ শিক্ষার্থী</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-book text-success"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ currentCourse.total_modules || '০' }}</h4>
                                <p>মোট মডিউল</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-play-circle text-warning"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ currentCourse.total_lessons || '০' }}</h4>
                                <p>মোট লেসন</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div class="stat-info">
                                <h4>{{ currentCourse.total_hours || '০' }}</h4>
                                <p>মোট ঘন্টা</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What You'll Learn -->
            <div v-if="currentCourse.what_you_learn" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-lightbulb mr-2"></i>
                    যা শিখবেন
                </h3>
                <div class="content-preview" v-html="currentCourse.what_you_learn"></div>
            </div>

            <!-- Course Requirements -->
            <div v-if="currentCourse.requirements" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-list-check mr-2"></i>
                    প্রয়োজনীয়তা
                </h3>
                <div class="content-preview" v-html="currentCourse.requirements"></div>
            </div>

            <!-- Course Curriculum -->
            <div v-if="currentCourse.curriculum" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-book-open mr-2"></i>
                    কোর্স কারিকুলাম
                </h3>
                <div class="content-preview" v-html="currentCourse.curriculum"></div>
            </div>

            <!-- Module Information -->
            <div v-if="hasModuleInfo" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-list mr-2"></i>
                    মডিউল তথ্য
                </h3>
                
                <div v-if="currentCourse.module_introduction" class="module-subsection">
                    <h5>মডিউল পরিচিতি</h5>
                    <div class="content-preview" v-html="currentCourse.module_introduction"></div>
                </div>
                
                <div v-if="currentCourse.module_overview" class="module-subsection">
                    <h5>মডিউল ওভারভিউ</h5>
                    <div class="content-preview" v-html="currentCourse.module_overview"></div>
                </div>
                
                <div v-if="currentCourse.module_prerequisites" class="module-subsection">
                    <h5>মডিউল পূর্বশর্ত</h5>
                    <p>{{ currentCourse.module_prerequisites }}</p>
                </div>
                
                <div v-if="currentCourse.module_outcome" class="module-subsection">
                    <h5>মডিউল শেষে অর্জন</h5>
                    <p>{{ currentCourse.module_outcome }}</p>
                </div>
            </div>

            <!-- Course Banner Information -->
            <div v-if="hasBannerInfo" class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-image mr-2"></i>
                    ব্যানার তথ্য
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div v-if="currentCourse.banner_image" class="banner-preview">
                            <img 
                                :src="getBannerUrl(currentCourse.banner_image)" 
                                :alt="currentCourse.banner_title || 'Course Banner'"
                                class="img-fluid rounded"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div v-if="currentCourse.banner_title" class="banner-info">
                            <h5>{{ currentCourse.banner_title }}</h5>
                        </div>
                        <div v-if="currentCourse.banner_subtitle" class="banner-info">
                            <p class="text-muted">{{ currentCourse.banner_subtitle }}</p>
                        </div>
                        <div v-if="currentCourse.banner_description" class="banner-info">
                            <div v-html="currentCourse.banner_description"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Status -->
            <div class="preview-section">
                <h3 class="section-title">
                    <i class="fas fa-info mr-2"></i>
                    কোর্স অবস্থা
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="status-item">
                            <strong>প্রকাশের অবস্থা:</strong>
                            <span :class="getStatusClass(currentCourse.status)">
                                {{ getStatusLabel(currentCourse.status) }}
                            </span>
                        </div>
                        <div class="status-item">
                            <strong>নিবন্ধন শুরু:</strong>
                            {{ formatDate(currentCourse.enrollment_start) }}
                        </div>
                        <div class="status-item">
                            <strong>নিবন্ধন শেষ:</strong>
                            {{ formatDate(currentCourse.enrollment_end) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-item">
                            <strong>কোর্স শুরু:</strong>
                            {{ formatDate(currentCourse.start_date) }}
                        </div>
                        <div class="status-item">
                            <strong>কোর্স শেষ:</strong>
                            {{ formatDate(currentCourse.end_date) }}
                        </div>
                        <div class="status-item">
                            <strong>সর্বশেষ আপডেট:</strong>
                            {{ formatDateTime(currentCourse.updated_at) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-5">
            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
            <h4>কোর্স তথ্য পাওয়া যায়নি</h4>
            <p class="text-muted">দয়া করে একটি কোর্স নির্বাচন করুন</p>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';

export default {
    name: 'CoursePreview',
    
    data() {
        return {
            loading: false,
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, [
            'currentCourse', 
            'categories'
        ]),
        
        hasModuleInfo() {
            if (!this.currentCourse) return false;
            return !!(
                this.currentCourse.module_introduction ||
                this.currentCourse.module_overview ||
                this.currentCourse.module_prerequisites ||
                this.currentCourse.module_outcome
            );
        },
        
        hasBannerInfo() {
            if (!this.currentCourse) return false;
            return !!(
                this.currentCourse.banner_image ||
                this.currentCourse.banner_title ||
                this.currentCourse.banner_subtitle ||
                this.currentCourse.banner_description
            );
        },
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, [
            'fetchCourse', 
            'fetchCategories'
        ]),
        
        async refreshPreview() {
            if (!this.currentCourse?.id) return;
            
            this.loading = true;
            try {
                await this.fetchCourse(this.currentCourse.id);
                this.$toast.success('প্রিভিউ রিফ্রেশ হয়েছে');
            } catch (error) {
                this.$toast.error('প্রিভিউ রিফ্রেশ করতে ত্রুটি হয়েছে');
                console.error('Error refreshing preview:', error);
            } finally {
                this.loading = false;
            }
        },
        
        exportPreview() {
            if (!this.currentCourse) return;
            
            const printWindow = window.open('', '_blank');
            const content = this.generatePrintContent();
            
            printWindow.document.write(content);
            printWindow.document.close();
            printWindow.print();
        },
        
        generatePrintContent() {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${this.currentCourse.title} - কোর্স প্রিভিউ</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .section { margin-bottom: 25px; }
                        .section-title { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
                        .meta-item { margin-right: 15px; }
                        .stat-card { display: inline-block; border: 1px solid #ddd; padding: 15px; margin: 10px; }
                        img { max-width: 300px; height: auto; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>${this.currentCourse.title}</h1>
                        <p>${this.currentCourse.subtitle || ''}</p>
                    </div>
                    ${this.currentCourse.description ? `<div class="section"><h3>কোর্স বিবরণ</h3>${this.currentCourse.description}</div>` : ''}
                    <!-- Add more sections as needed -->
                </body>
                </html>
            `;
        },
        
        getCategoryName(categoryId) {
            if (!this.categories || !Array.isArray(this.categories)) {
                return 'ক্যাটেগরি লোড হচ্ছে...';
            }
            const category = this.categories.find(cat => cat.id === categoryId);
            return category?.name || 'ক্যাটেগরি নির্বাচিত নয়';
        },
        
        getDifficultyLabel(level) {
            const labels = {
                'beginner': 'শুরুর স্তর',
                'intermediate': 'মধ্যম স্তর',
                'advanced': 'উন্নত স্তর'
            };
            return labels[level] || 'স্তর নির্ধারিত নয়';
        },
        
        getStatusLabel(status) {
            const labels = {
                'draft': 'খসড়া',
                'published': 'প্রকাশিত',
                'archived': 'সংরক্ষিত'
            };
            return labels[status] || 'অজানা';
        },
        
        getStatusClass(status) {
            const classes = {
                'draft': 'badge badge-warning',
                'published': 'badge badge-success',
                'archived': 'badge badge-secondary'
            };
            return classes[status] || 'badge badge-light';
        },
        
        getThumbnailUrl(thumbnail) {
            return thumbnail.startsWith('http') ? thumbnail : `/storage/${thumbnail}`;
        },
        
        getBannerUrl(banner) {
            return banner.startsWith('http') ? banner : `/storage/${banner}`;
        },
        
        formatPrice(price) {
            return price ? parseFloat(price).toLocaleString('bn-BD') : '০';
        },
        
        formatDate(date) {
            if (!date) return 'নির্ধারিত নয়';
            return new Date(date).toLocaleDateString('bn-BD');
        },
        
        formatDateTime(date) {
            if (!date) return 'নির্ধারিত নয়';
            return new Date(date).toLocaleString('bn-BD');
        },
    },
    
    async mounted() {
        if (!this.categories || !this.categories.length) {
            await this.fetchCategories();
        }
    },
};
</script>

<style scoped>
.course-preview {
    max-width: 100%;
}

.course-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.course-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.course-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 20px;
}

.course-meta .meta-item {
    display: inline-block;
    margin-right: 20px;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.course-meta .meta-item i {
    margin-right: 5px;
}

.course-thumbnail img {
    max-width: 100%;
    max-height: 200px;
    object-fit: cover;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.no-thumbnail {
    background-color: rgba(255, 255, 255, 0.1);
    padding: 40px;
    text-align: center;
    border-radius: 8px;
    border: 2px dashed rgba(255, 255, 255, 0.3);
}

.no-thumbnail i {
    font-size: 3rem;
    opacity: 0.5;
}

.preview-section {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.section-title {
    color: #495057;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.content-preview {
    line-height: 1.6;
    color: #495057;
}

.content-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin: 10px 0;
}

.stat-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 15px;
}

.stat-info h4 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #495057;
}

.stat-info p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.module-subsection {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.module-subsection:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.module-subsection h5 {
    color: #007bff;
    font-weight: 600;
    margin-bottom: 10px;
}

.banner-preview img {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
}

.banner-info h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.status-item {
    margin-bottom: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.status-item strong {
    color: #495057;
    margin-right: 10px;
}

.header-actions .btn {
    border-radius: 20px;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-title {
        font-size: 2rem;
    }
    
    .course-subtitle {
        font-size: 1rem;
    }
    
    .course-meta .meta-item {
        display: block;
        margin-bottom: 8px;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
    
    .header-actions {
        margin-top: 15px;
    }
    
    .header-actions .btn {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>
