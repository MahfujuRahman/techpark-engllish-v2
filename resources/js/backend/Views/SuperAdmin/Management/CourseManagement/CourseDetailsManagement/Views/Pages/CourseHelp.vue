<template>
    <div class="course-help">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle mr-2"></i>
                    এই কোর্সটি আপনাকে কিভাবে সাহায্য করবে
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveHelpContent">
                    <div class="form-group">
                        <label for="help_content" class="form-label">
                            কোর্সটি কিভাবে সাহায্য করবে সে সম্পর্কে বিস্তারিত লিখুন
                        </label>
                        <div 
                            id="help_content"
                            ref="editor"
                            class="ck-editor-container"
                            :class="{ 'is-invalid': errors.help_content }"
                        ></div>
                        <div v-if="errors.help_content" class="invalid-feedback">
                            {{ errors.help_content[0] }}
                        </div>
                    </div>

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
                            @click="resetContent"
                            class="btn btn-secondary ml-2"
                        >
                            <i class="fas fa-undo mr-1"></i>
                            রিসেট করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div class="card mt-4" v-if="helpContent">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-eye mr-1"></i>
                    প্রিভিউ
                </h6>
            </div>
            <div class="card-body">
                <div v-html="helpContent" class="preview-content"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';

export default {
    name: 'CourseHelp',
    
    data() {
        return {
            editor: null,
            helpContent: '',
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
        
        async saveHelpContent() {
            this.clearErrors();
            
            if (!this.editor) {
                this.$toast.error('এডিটর লোড হয়নি, অনুগ্রহ করে পেজ রিফ্রেশ করুন');
                return;
            }
            
            try {
                const content = this.editor.getData();
                const formData = new FormData();
                formData.append('help_content', content);
                formData.append('_method', 'PUT');
                
                await this.updateCourse(this.currentCourse.id, formData);
                
                this.helpContent = content;
                this.$toast.success('কোর্স সাহায্য কন্টেন্ট সফলভাবে আপডেট হয়েছে!');
                
            } catch (error) {
                this.$toast.error('কোর্স সাহায্য কন্টেন্ট আপডেট করতে ত্রুটি হয়েছে!');
                console.error('Error saving help content:', error);
            }
        },
        
        resetContent() {
            if (this.editor && this.currentCourse) {
                const originalContent = this.currentCourse.help_content || '';
                this.editor.setData(originalContent);
                this.helpContent = originalContent;
            }
            this.clearErrors();
        },
        
        async initializeCKEditor() {
            try {
                if (window.ClassicEditor) {
                    this.editor = await window.ClassicEditor.create(this.$refs.editor, {
                        placeholder: 'এই কোর্সটি কিভাবে আপনাকে সাহায্য করবে তা বিস্তারিত লিখুন...',
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
                    const initialContent = this.currentCourse?.help_content || '';
                    this.editor.setData(initialContent);
                    this.helpContent = initialContent;
                    
                    // Update preview on content change
                    this.editor.model.document.on('change:data', () => {
                        this.helpContent = this.editor.getData();
                    });
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
                if (newCourse && this.editor) {
                    const content = newCourse.help_content || '';
                    this.editor.setData(content);
                    this.helpContent = content;
                }
            },
            immediate: false
        }
    }
};
</script>

<style scoped>
.course-help {
    max-width: 100%;
}

.ck-editor-container {
    min-height: 300px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.ck-editor-container.is-invalid {
    border-color: #dc3545;
}

.form-actions {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.preview-content {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    min-height: 200px;
}

.preview-content:empty::before {
    content: 'কন্টেন্ট লিখুন প্রিভিউ দেখতে...';
    color: #6c757d;
    font-style: italic;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

/* Preview content styling */
.preview-content h1,
.preview-content h2,
.preview-content h3,
.preview-content h4,
.preview-content h5,
.preview-content h6 {
    color: #333;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.preview-content h1 { font-size: 1.75rem; }
.preview-content h2 { font-size: 1.5rem; }
.preview-content h3 { font-size: 1.25rem; }

.preview-content p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.preview-content ul,
.preview-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.preview-content li {
    margin-bottom: 0.5rem;
}

.preview-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    background-color: #fff;
}

.preview-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.preview-content table th,
.preview-content table td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    text-align: left;
}

.preview-content table th {
    background-color: #e9ecef;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions {
        text-align: center;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
}
</style>
