import { defineStore } from 'pinia';
import axios from 'axios';

export const useCourseDetailsStore = defineStore('courseDetails', {
    state: () => ({
        // Course data
        courses: {},
        currentCourse: null,
        courseDetails: {},
        
        // UI states
        loading: false,
        submitting: false,
        
        // Pagination
        page: 1,
        paginate: 10,
        
        // Filters
        filterCriteria: {
            search: '',
            status: '',
            type: '',
            date_from: '',
            date_to: '',
        },
        
        // Modals and UI states
        showDetailsCanvas: false,
        showFilterCanvas: false,
        showImportModal: false,
        
        // Course sub-modules data
        courseBatches: {},
        courseWhatLearn: {},
        courseJobPositions: {},
        courseForWhom: {},
        courseWhyLearn: {},
        courseTrainers: {},
        courseMilestones: {},
        courseClasses: {},
        courseFaqs: {},
        courseModules: {},
        courseRoutines: {},
        
        // Categories data
        categories: [],
        
        // Selected items for bulk actions
        selectedItems: [],
        allItemsSelected: false,
        
        // Trash data
        isTrashedData: false,
        
        // Form data
        formData: {
            id: null,
            course_category_id: null,
            title: '',
            slug: '',
            image: null,
            intro_video: '',
            published_at: null,
            is_published: 0,
            what_is_this_course: '',
            why_is_this_course: '',
            status: 'active',
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
        },
        
        // Error handling
        errors: {},
        errorMessage: '',
        successMessage: '',
    }),

    getters: {
        // Get current course from localStorage if not in state
        getCurrentCourse: (state) => {
            if (state.currentCourse) {
                return state.currentCourse;
            }
            
            const stored = localStorage.getItem('current_course');
            if (stored) {
                try {
                    return JSON.parse(stored);
                } catch (e) {
                    console.error('Error parsing stored course:', e);
                    return null;
                }
            }
            return null;
        },
        
        // Check if user has selected items
        hasSelectedItems: (state) => state.selectedItems.length > 0,
        
        // Get selected items count
        selectedItemsCount: (state) => state.selectedItems.length,
        
        // Check if form has changes
        hasFormChanges: (state) => {
            return Object.keys(state.formData).some(key => {
                return state.formData[key] !== (state.currentCourse?.[key] || '');
            });
        },
        
        // Get form errors for specific field
        getFieldErrors: (state) => (field) => {
            return state.errors[field] || [];
        },
        
        // Check if form is valid
        isFormValid: (state) => {
            return Object.keys(state.errors).length === 0;
        },
    },

    actions: {
        // ====================
        // COURSE CRUD ACTIONS
        // ====================
        
        async getCourses() {
            this.loading = true;
            this.errorMessage = '';
            
            try {
                const params = {
                    page: this.page,
                    paginate: this.paginate,
                    search: this.filterCriteria.search,
                    status: this.filterCriteria.status,
                    type: this.filterCriteria.type,
                    date_from: this.filterCriteria.date_from,
                    date_to: this.filterCriteria.date_to,
                    trashed: this.isTrashedData,
                };
                
                // Remove empty parameters
                Object.keys(params).forEach(key => {
                    if (!params[key] && params[key] !== 0) {
                        delete params[key];
                    }
                });
                
                const response = await axios.get('courses', { params });
                this.courses = response.data.data;
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'কোর্স তালিকা লোড করতে ত্রুটি হয়েছে';
                console.error('Error fetching courses:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        async getCourseDetails(slug) {
            this.loading = true;
            this.errorMessage = '';
            
            try {
                const response = await axios.get(`courses/${slug}`);
                this.courseDetails = response.data;
                this.currentCourse = response.data.data;
                
                // Store in localStorage for navigation
                localStorage.setItem('current_course', JSON.stringify(this.currentCourse));
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'কোর্সের বিস্তারিত তথ্য লোড করতে ত্রুটি হয়েছে';
                console.error('Error fetching course details:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        async createCourse(formData) {
            this.submitting = true;
            this.errors = {};
            this.errorMessage = '';
            this.successMessage = '';
            
            try {
                const config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                };
                
                const response = await axios.post('courses/store', formData, config);
                
                this.successMessage = 'কোর্স সফলভাবে তৈরি হয়েছে!';
                
                // Reset form
                this.resetFormData();
                
                return response.data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.errorMessage = 'কোর্স তৈরি করতে ত্রুটি হয়েছে';
                }
                console.error('Error creating course:', error);
                return error.response;
            } finally {
                this.submitting = false;
            }
        },
        
        async updateCourse(slug, formData) {
            this.submitting = true;
            this.errors = {};
            this.errorMessage = '';
            this.successMessage = '';
            
            try {
                const config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                };
                
                const response = await axios.post(`courses/update/${slug}`, formData, config);
                
                this.successMessage = 'কোর্স সফলভাবে আপডেট হয়েছে!';
                
                // Update current course
                this.currentCourse = response.data.data;
                localStorage.setItem('current_course', JSON.stringify(this.currentCourse));
                
                return response.data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.errorMessage = 'কোর্স আপডেট করতে ত্রুটি হয়েছে';
                }
                console.error('Error updating course:', error);
                return error.response;
            } finally {
                this.submitting = false;
            }
        },
        
        async deleteCourse(slug) {
            this.loading = true;
            this.errorMessage = '';
            
            try {
                const response = await axios.post('courses/soft-delete', { slug: slug });
                this.successMessage = 'কোর্স সফলভাবে মুছে ফেলা হয়েছে!';
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'কোর্স মুছে ফেলতে ত্রুটি হয়েছে';
                console.error('Error deleting course:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        async restoreCourse(id) {
            this.loading = true;
            this.errorMessage = '';
            
            try {
                const response = await axios.post('courses/restore', { id });
                this.successMessage = 'কোর্স সফলভাবে পুনরুদ্ধার হয়েছে!';
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'কোর্স পুনরুদ্ধার করতে ত্রুটি হয়েছে';
                console.error('Error restoring course:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        async destroyCourse(slug) {
            this.loading = true;
            this.errorMessage = '';
            
            try {
                const response = await axios.post(`courses/destroy/${slug}`);
                this.successMessage = 'কোর্স স্থায়ীভাবে মুছে ফেলা হয়েছে!';
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'কোর্স স্থায়ীভাবে মুছে ফেলতে ত্রুটি হয়েছে';
                console.error('Error destroying course:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        // ====================
        // PAGINATION ACTIONS
        // ====================
        
        setPage(page) {
            this.page = page;
        },
        
        setPaginate(paginate) {
            this.paginate = paginate;
            this.page = 1; // Reset to first page when changing page size
        },
        
        // ====================
        // FILTER ACTIONS
        // ====================
        
        setFilterCriteria(criteria) {
            this.filterCriteria = { ...this.filterCriteria, ...criteria };
            this.page = 1; // Reset to first page when filtering
        },
        
        resetFilterCriteria() {
            this.filterCriteria = {
                search: '',
                status: '',
                type: '',
                date_from: '',
                date_to: '',
            };
            this.page = 1;
        },
        
        // ====================
        // UI STATE ACTIONS
        // ====================
        
        setShowDetailsCanvas(show) {
            this.showDetailsCanvas = show;
        },
        
        setShowFilterCanvas(show) {
            this.showFilterCanvas = show;
        },
        
        setShowImportModal(show) {
            this.showImportModal = show;
        },
        
        setIsTrashedData(isTrashed) {
            this.isTrashedData = isTrashed;
            this.page = 1; // Reset pagination
        },
        
        // ====================
        // SELECTION ACTIONS
        // ====================
        
        setSelectedItems(items) {
            this.selectedItems = [...items];
        },
        
        addSelectedItem(item) {
            if (!this.selectedItems.includes(item)) {
                this.selectedItems.push(item);
            }
        },
        
        removeSelectedItem(item) {
            this.selectedItems = this.selectedItems.filter(id => id !== item);
        },
        
        toggleSelectItem(item) {
            if (this.selectedItems.includes(item)) {
                this.removeSelectedItem(item);
            } else {
                this.addSelectedItem(item);
            }
        },
        
        selectAllItems() {
            if (this.courses.data) {
                this.selectedItems = this.courses.data.map(item => item.id);
                this.allItemsSelected = true;
            }
        },
        
        clearSelectedItems() {
            this.selectedItems = [];
            this.allItemsSelected = false;
        },
        
        // ====================
        // BULK ACTIONS
        // ====================
        
        async bulkAction(action, ids = null) {
            this.loading = true;
            this.errorMessage = '';
            
            const targetIds = ids || this.selectedItems;
            
            if (!targetIds.length) {
                this.errorMessage = 'কোন আইটেম নির্বাচিত নেই';
                this.loading = false;
                return;
            }
            
            try {
                const response = await axios.post('courses/bulk-action', {
                    action,
                    ids: targetIds,
                });
                
                this.successMessage = `${targetIds.length} টি কোর্সে ${action} প্রয়োগ করা হয়েছে`;
                this.clearSelectedItems();
                
                return response.data;
            } catch (error) {
                this.errorMessage = 'বাল্ক অ্যাকশন সম্পাদনে ত্রুটি হয়েছে';
                console.error('Error performing bulk action:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
        
        // ====================
        // FORM ACTIONS
        // ====================
        
        setFormData(data) {
            this.formData = { ...this.formData, ...data };
        },
        
        resetFormData() {
            this.formData = {
                id: null,
                course_category_id: null,
                title: '',
                slug: '',
                image: null,
                intro_video: '',
                published_at: null,
                is_published: 0,
                what_is_this_course: '',
                why_is_this_course: '',
                status: 'active',
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
            };
        },
        
        populateFormWithCourse(course) {
            this.formData = {
                id: course.id || null,
                course_category_id: course.course_category_id || null,
                title: course.title || '',
                slug: course.slug || '',
                image: null, // Always null for file inputs
                intro_video: course.intro_video || '',
                published_at: course.published_at || null,
                is_published: course.is_published !== undefined ? course.is_published : 0,
                status: course.status || 'active',
                meta_title: course.meta_title || '',
                meta_description: course.meta_description || '',
                meta_keywords: course.meta_keywords || '',
            };
        },
        
        clearErrors() {
            this.errors = {};
            this.errorMessage = '';
        },
        
        clearMessages() {
            this.errorMessage = '';
            this.successMessage = '';
        },

        // ====================
        // CATEGORIES ACTIONS
        // ====================
        
        async fetchCategories() {
            try {
                // Assuming categories API endpoint exists
                // You may need to adjust this endpoint based on your actual API
                const response = await axios.get('/api/v1/categories');
                this.categories = response.data.data || response.data || [];
            } catch (error) {
                console.error('Error fetching categories:', error);
                this.categories = [];
            }
        },
        
        // ====================
        // UTILITY ACTIONS
        // ====================
        
        generateSlug(title) {
            return title
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .trim();
        },
        
        // Set current course
        setCurrentCourse(course) {
            this.currentCourse = course;
            if (course) {
                localStorage.setItem('current_course', JSON.stringify(course));
            } else {
                localStorage.removeItem('current_course');
            }
        },
        
        // Clear all data (useful for route changes)
        clearStore() {
            this.courses = {};
            this.currentCourse = null;
            this.courseDetails = {};
            this.categories = [];
            this.clearSelectedItems();
            this.clearErrors();
            this.clearMessages();
            this.resetFilterCriteria();
        },
    },
});
