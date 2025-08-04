<template>
    <div class="course-module-text">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list mr-2"></i>
                    কোর্সে মডিউল তথ্য
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    এই সেকশনে কোর্সের মডিউল সম্পর্কে সাধারণ তথ্য এবং বিবরণ যোগ করুন।
                </div>

                <form @submit.prevent="saveModuleText">
                    <div class="form-group">
                        <label for="module_introduction" class="form-label">মডিউল পরিচিতি</label>
                        <div 
                            id="module_introduction"
                            ref="introEditor"
                            class="ck-editor-container"
                            :class="{ 'is-invalid': errors.module_introduction }"
                        ></div>
                        <small class="form-text text-muted">
                            কোর্সের মডিউল সম্পর্কে সংক্ষিপ্ত পরিচিতি লিখুন
                        </small>
                        <div v-if="errors.module_introduction" class="invalid-feedback">
                            {{ errors.module_introduction[0] }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_overview" class="form-label">মডিউল ওভারভিউ</label>
                        <div 
                            id="module_overview"
                            ref="overviewEditor"
                            class="ck-editor-container"
                            :class="{ 'is-invalid': errors.module_overview }"
                        ></div>
                        <small class="form-text text-muted">
                            কোর্সের সামগ্রিক মডিউল কাঠামো সম্পর্কে বিস্তারিত তথ্য
                        </small>
                        <div v-if="errors.module_overview" class="invalid-feedback">
                            {{ errors.module_overview[0] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_modules" class="form-label">মোট মডিউল সংখ্যা</label>
                                <input 
                                    type="number" 
                                    id="total_modules"
                                    v-model="formData.total_modules"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.total_modules }"
                                    placeholder="১২"
                                    min="0"
                                >
                                <div v-if="errors.total_modules" class="invalid-feedback">
                                    {{ errors.total_modules[0] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_lessons" class="form-label">মোট লেসন সংখ্যা</label>
                                <input 
                                    type="number" 
                                    id="total_lessons"
                                    v-model="formData.total_lessons"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.total_lessons }"
                                    placeholder="৮৫"
                                    min="0"
                                >
                                <div v-if="errors.total_lessons" class="invalid-feedback">
                                    {{ errors.total_lessons[0] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_hours" class="form-label">মোট ঘন্টা</label>
                                <input 
                                    type="number" 
                                    id="total_hours"
                                    v-model="formData.total_hours"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.total_hours }"
                                    placeholder="১২০"
                                    min="0"
                                    step="0.5"
                                >
                                <div v-if="errors.total_hours" class="invalid-feedback">
                                    {{ errors.total_hours[0] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="module_difficulty" class="form-label">মডিউলের কঠিনতা স্তর</label>
                                <select 
                                    id="module_difficulty"
                                    v-model="formData.module_difficulty"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.module_difficulty }"
                                >
                                    <option value="">স্তর নির্বাচন করুন</option>
                                    <option value="beginner">শুরুর স্তর</option>
                                    <option value="intermediate">মধ্যম স্তর</option>
                                    <option value="advanced">উন্নত স্তর</option>
                                    <option value="mixed">মিশ্র স্তর</option>
                                </select>
                                <div v-if="errors.module_difficulty" class="invalid-feedback">
                                    {{ errors.module_difficulty[0] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_prerequisites" class="form-label">মডিউল পূর্বশর্ত</label>
                        <textarea 
                            id="module_prerequisites"
                            v-model="formData.module_prerequisites"
                            class="form-control"
                            :class="{ 'is-invalid': errors.module_prerequisites }"
                            rows="3"
                            placeholder="মডিউল শুরু করার আগে কী কী জানা থাকতে হবে..."
                        ></textarea>
                        <div v-if="errors.module_prerequisites" class="invalid-feedback">
                            {{ errors.module_prerequisites[0] }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_outcome" class="form-label">মডিউল শেষে অর্জন</label>
                        <textarea 
                            id="module_outcome"
                            v-model="formData.module_outcome"
                            class="form-control"
                            :class="{ 'is-invalid': errors.module_outcome }"
                            rows="4"
                            placeholder="মডিউল সম্পন্ন করার পর শিক্ষার্থীরা কী কী শিখবে..."
                        ></textarea>
                        <div v-if="errors.module_outcome" class="invalid-feedback">
                            {{ errors.module_outcome[0] }}
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
                            {{ submitting ? 'সংরক্ষণ হচ্ছে...' : 'মডিউল তথ্য সংরক্ষণ করুন' }}
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
            </div>
        </div>

        <!-- Module Statistics Preview -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar mr-1"></i>
                    মডিউল পরিসংখ্যান
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-number">{{ formData.total_modules || 0 }}</div>
                            <div class="stat-label">মোট মডিউল</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-number">{{ formData.total_lessons || 0 }}</div>
                            <div class="stat-label">মোট লেসন</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-number">{{ formData.total_hours || 0 }}</div>
                            <div class="stat-label">মোট ঘন্টা</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-number">
                                <span class="badge badge-info">{{ getDifficultyLabel(formData.module_difficulty) }}</span>
                            </div>
                            <div class="stat-label">কঠিনতা স্তর</div>
                        </div>
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
    name: 'CourseModuleText',
    
    data() {
        return {
            introEditor: null,
            overviewEditor: null,
            formData: {
                module_introduction: '',
                module_overview: '',
                total_modules: '',
                total_lessons: '',
                total_hours: '',
                module_difficulty: '',
                module_prerequisites: '',
                module_outcome: '',
            },
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, [
            'currentCourse', 
            'submitting', 
            'errors'
        ]),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, [
            'updateCourse', 
            'clearErrors'
        ]),
        
        async saveModuleText() {
            this.clearErrors();
            
            try {
                const formData = new FormData();
                
                // Add text fields
                Object.keys(this.formData).forEach(key => {
                    if (this.formData[key] !== null && this.formData[key] !== '') {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                // Add editor content
                if (this.introEditor) {
                    formData.append('module_introduction', this.introEditor.getData());
                }
                
                if (this.overviewEditor) {
                    formData.append('module_overview', this.overviewEditor.getData());
                }
                
                formData.append('_method', 'PUT');
                
                await this.updateCourse(this.currentCourse.id, formData);
                
                this.$toast.success('কোর্স মডিউল তথ্য সফলভাবে আপডেট হয়েছে!');
                
            } catch (error) {
                this.$toast.error('কোর্স মডিউল তথ্য আপডেট করতে ত্রুটি হয়েছে!');
                console.error('Error saving module text:', error);
            }
        },
        
        resetForm() {
            if (this.currentCourse) {
                this.populateFormData();
                
                if (this.introEditor) {
                    this.introEditor.setData(this.currentCourse.module_introduction || '');
                }
                
                if (this.overviewEditor) {
                    this.overviewEditor.setData(this.currentCourse.module_overview || '');
                }
            }
            this.clearErrors();
        },
        
        populateFormData() {
            if (this.currentCourse) {
                this.formData = {
                    module_introduction: this.currentCourse.module_introduction || '',
                    module_overview: this.currentCourse.module_overview || '',
                    total_modules: this.currentCourse.total_modules || '',
                    total_lessons: this.currentCourse.total_lessons || '',
                    total_hours: this.currentCourse.total_hours || '',
                    module_difficulty: this.currentCourse.module_difficulty || '',
                    module_prerequisites: this.currentCourse.module_prerequisites || '',
                    module_outcome: this.currentCourse.module_outcome || '',
                };
            }
        },
        
        getDifficultyLabel(difficulty) {
            const labels = {
                'beginner': 'শুরুর স্তর',
                'intermediate': 'মধ্যম স্তর',
                'advanced': 'উন্নত স্তর',
                'mixed': 'মিশ্র স্তর'
            };
            return labels[difficulty] || 'নির্বাচিত নয়';
        },
        
        async initializeCKEditor() {
            try {
                if (window.ClassicEditor) {
                    // Initialize introduction editor
                    this.introEditor = await window.ClassicEditor.create(this.$refs.introEditor, {
                        placeholder: 'মডিউল পরিচিতি লিখুন...',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'undo', 'redo'
                        ]
                    });
                    
                    // Initialize overview editor
                    this.overviewEditor = await window.ClassicEditor.create(this.$refs.overviewEditor, {
                        placeholder: 'মডিউল ওভারভিউ লিখুন...',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    });
                    
                    // Set initial content
                    if (this.currentCourse) {
                        this.introEditor.setData(this.currentCourse.module_introduction || '');
                        this.overviewEditor.setData(this.currentCourse.module_overview || '');
                    }
                }
            } catch (error) {
                console.error('Error initializing CKEditor:', error);
                this.$toast.error('এডিটর লোড করতে ত্রুটি হয়েছে');
            }
        },
    },
    
    async mounted() {
        if (!this.currentCourse) {
            this.$toast.error('কোর্স তথ্য পাওয়া যায়নি');
            return;
        }
        
        this.populateFormData();
        
        // Initialize CKEditor
        await this.$nextTick();
        await this.initializeCKEditor();
    },
    
    beforeUnmount() {
        if (this.introEditor) {
            this.introEditor.destroy();
        }
        if (this.overviewEditor) {
            this.overviewEditor.destroy();
        }
    },
    
    watch: {
        currentCourse: {
            handler(newCourse) {
                if (newCourse) {
                    this.populateFormData();
                    
                    if (this.introEditor) {
                        this.introEditor.setData(newCourse.module_introduction || '');
                    }
                    
                    if (this.overviewEditor) {
                        this.overviewEditor.setData(newCourse.module_overview || '');
                    }
                }
            },
            immediate: false
        }
    }
};
</script>

<style scoped>
.course-module-text {
    max-width: 100%;
}

.ck-editor-container {
    min-height: 150px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    margin-bottom: 10px;
}

.ck-editor-container.is-invalid {
    border-color: #dc3545;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.stat-card {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #007bff;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-number {
        font-size: 1.5rem;
    }
    
    .stat-card {
        padding: 15px;
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
