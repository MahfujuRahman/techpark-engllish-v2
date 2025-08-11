<template>
    <div class="course-class-quiz-form">
        <!-- Show error message if no course data -->
        <div v-if="!loading && !courseId" class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Course information is not available. Please go back and select a valid course.
        </div>
        
        <div v-else class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i :class="isEditMode ? 'fas fa-edit mr-2' : 'fas fa-plus mr-2'"></i>
                    {{ isEditMode ? 'Edit Class Quiz' : 'Create New Class Quiz' }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <!-- Filter Section: Milestone and Module Selection -->
                    <div class="form-section filter-section">
                        <h6 class="section-title">Filter by Milestone & Module (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Filter by Milestone</label>
                                    <select v-model="filterMilestone" @change="onFilterChange" class="form-control" :disabled="loading">
                                        <option value="">-- Show All Milestones --</option>
                                        <option v-for="milestone in allMilestones" :key="milestone.id" :value="milestone.id">
                                            {{ milestone.title }}
                                        </option>
                                    </select>
                                    <small v-if="!loading && allMilestones.length === 0" class="text-muted">No milestones available</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Filter by Module</label>
                                    <select v-model="filterModule" @change="onFilterChange" class="form-control" :disabled="loading">
                                        <option value="">-- Show All Modules --</option>
                                        <option v-for="module in filteredModulesForFilter" :key="module.id" :value="module.id">
                                            {{ module.title }}
                                        </option>
                                    </select>
                                    <small v-if="!loading && allModules.length === 0" class="text-muted">No modules available</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Class and Quiz Selection Section -->
                    <div class="form-section class-quiz-section">
                        <h6 class="section-title">Select a Class and Assign Quizzes</h6>
                        
                        <div class="row">
                            <!-- Left Side: Classes List -->
                            <div class="col-md-6">
                                <div class="classes-panel">
                                    <div class="panel-header">
                                        <h6 class="panel-title">Classes (Select One)</h6>
                                        <div class="search-box">
                                            <input 
                                                type="text" 
                                                v-model="classSearch" 
                                                class="form-control form-control-sm" 
                                                placeholder="Search classes..."
                                            >
                                            <i class="fas fa-search search-icon"></i>
                                        </div>
                                    </div>
                                    <div class="panel-content">
                                        <div v-if="loading" class="no-data">
                                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                            <p>Loading classes...</p>
                                        </div>
                                        <div v-else-if="filteredClasses.length === 0 && allClasses.length === 0" class="no-data">
                                            <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                            <p>No classes available for this course</p>
                                        </div>
                                        <div v-else-if="filteredClasses.length === 0" class="no-data">
                                            <i class="fas fa-filter fa-2x mb-2"></i>
                                            <p>No classes match the current filters</p>
                                        </div>
                                        <div 
                                            v-for="classItem in filteredClasses" 
                                            :key="classItem.id"
                                            class="class-item"
                                            :class="{ 'selected': isClassSelected(classItem.id) }"
                                            @click="selectClass(classItem.id)"
                                        >
                                            <div class="selection-radio">
                                                <input 
                                                    type="radio" 
                                                    :name="'classSelection'"
                                                    :checked="isClassSelected(classItem.id)"
                                                    @change="selectClass(classItem.id)"
                                                    class="form-check-input"
                                                >
                                            </div>
                                            <div class="class-info">
                                                <h6 class="class-name">{{ classItem.title }}</h6>
                                                <!-- <small class="class-description">
                                                    <span class="milestone-badge">{{ getMilestoneName(classItem.milestone_id) }}</span>
                                                    <span class="module-badge">{{ getModuleName(classItem.course_modules_id) }}</span>
                                                </small> -->
                                            </div>
                                            <!-- <div class="class-actions">
                                                <span class="quiz-count">
                                                    {{ getClassQuizCount(classItem.id) }} quiz(es)
                                                </span>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Quiz List -->
                            <div class="col-md-6">
                                <div class="quizzes-panel">
                                    <div class="panel-header">
                                        <h6 class="panel-title">
                                            Quizzes
                                            <!-- <span v-if="selectedClass" class="text-muted">
                                                (for {{ selectedClass.title }})
                                            </span> -->
                                        </h6>
                                        <div class="search-box">
                                            <input 
                                                type="text" 
                                                v-model="quizSearch" 
                                                class="form-control form-control-sm" 
                                                placeholder="Search quizzes..."
                                            >
                                            <i class="fas fa-search search-icon"></i>
                                        </div>
                                    </div>
                                    <div class="panel-content">
                                        <div v-if="loading" class="no-data">
                                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                            <p>Loading quizzes...</p>
                                        </div>
                                        <div v-else-if="filteredQuizzes.length === 0 && allQuizzes.length === 0" class="no-data">
                                            <i class="fas fa-question-circle fa-2x mb-2"></i>
                                            <p>No quizzes available</p>
                                        </div>
                                        <div v-else-if="filteredQuizzes.length === 0" class="no-data">
                                            <i class="fas fa-search fa-2x mb-2"></i>
                                            <p>No quizzes match your search</p>
                                        </div>
                                        <div 
                                            v-for="quiz in filteredQuizzes" 
                                            :key="quiz.id"
                                            class="quiz-item"
                                            :class="{ disabled: !selectedClassId }"
                                            style="cursor: pointer;"
                                            @click="selectedClassId && toggleQuizForSelectedClass(quiz.id, !isQuizAssignedToSelectedClass(quiz.id))"
                                        >
                                            <div class="quiz-selection">
                                                <input 
                                                    type="checkbox" 
                                                    :checked="isQuizAssignedToSelectedClass(quiz.id)"
                                                    @change="toggleQuizForSelectedClass(quiz.id, $event.target.checked)"
                                                    :disabled="!selectedClassId"
                                                    class="form-check-input"
                                                >
                                            </div>
                                            <div class="quiz-info">
                                                <h6 class="quiz-title">{{ quiz.title }}</h6>
                                                <!-- <small class="quiz-description">{{ quiz.description || 'No description' }}</small> -->
                                            </div>
                                        </div>
                                        <div v-if="!selectedClassId && filteredQuizzes.length > 0" class="no-class-selected">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                                            <p>Please select a class first to assign quizzes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                :disabled="submitting || assignments.length === 0"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else class="fas fa-save mr-1"></i>
                                {{ submitting ? 'Processing...' : 'Save Class Quiz Assignments' }}
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
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';

export default {
    name: 'CourseQuizForm',
    
    data() {
        return {
            submitting: false,
            loading: false,
            errors: {},
            
            // All data loaded initially
            allMilestones: [],
            allModules: [],
            allClasses: [],
            allQuizzes: [],
            
            // Filter options
            filterMilestone: '',
            filterModule: '',
            
            // Search terms
            classSearch: '',
            quizSearch: '',
            
            // Selected items
            selectedClassId: null, // Single class selection
            assignments: [],
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
        
        isEditMode() {
            return this.$route.name === 'CourseQuizEdit' && this.$route.params.slug;
        },
        
        courseId() {
            return this.currentCourse?.id;
        },
        
        // Filter modules by selected milestone for filter dropdown
        filteredModulesForFilter() {
            if (!this.filterMilestone) return this.allModules;
            return this.allModules.filter(module => module.milestone_id == this.filterMilestone);
        },
        
        // Filter classes by search term and optional filters
        filteredClasses() {
            let classes = this.allClasses;
            
            // Apply milestone filter
            if (this.filterMilestone) {
                classes = classes.filter(cls => cls.milestone_id == this.filterMilestone);
            }
            
            // Apply module filter
            if (this.filterModule) {
                classes = classes.filter(cls => cls.course_modules_id == this.filterModule);
            }
            
            // Apply search filter
            if (this.classSearch) {
                classes = classes.filter(cls => 
                    cls.title.toLowerCase().includes(this.classSearch.toLowerCase()) ||
                    (cls.description && cls.description.toLowerCase().includes(this.classSearch.toLowerCase()))
                );
            }
            
            return classes;
        },
        
        // Filter quizzes by search term
        filteredQuizzes() {
            if (!this.quizSearch) return this.allQuizzes;
            return this.allQuizzes.filter(quiz => 
                quiz.title.toLowerCase().includes(this.quizSearch.toLowerCase()) ||
                (quiz.description && quiz.description.toLowerCase().includes(this.quizSearch.toLowerCase()))
            );
        },
        
        // Get selected class as object
        selectedClass() {
            if (!this.selectedClassId) return null;
            return this.allClasses.find(cls => cls.id === this.selectedClassId);
        },

        // Get quizzes assigned to the selected class
        assignedQuizIds() {
            if (!this.selectedClassId) return [];
            return this.assignments
                .filter(a => a.course_class_id === this.selectedClassId)
                .map(a => a.course_quiz_id);
        },
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        // Load initial data
        async loadInitialData() {
            if (!this.courseId) {
                console.warn('No course ID available for loading data');
                return;
            }
            
            console.log('Starting to load initial data for course ID:', this.courseId);
            
            this.loading = true;
            try {
                // Load all data initially
                await Promise.all([
                    this.loadAllMilestones(),
                    this.loadAllModules(),
                    this.loadAllClasses(),
                    this.loadAllQuizzes(),
                ]);
                
                console.log('Data loaded successfully:', {
                    milestones: this.allMilestones.length,
                    modules: this.allModules.length,
                    classes: this.allClasses.length,
                    quizzes: this.allQuizzes.length
                });
                
            } catch (error) {
                console.error('Error loading initial data:', error);
                window.s_error('Failed to load form data');
            } finally {
                this.loading = false;
            }
        },
        
        async loadAllMilestones() {
            try {
                const response = await axios.get(`course-milestones?course_id=${this.courseId}`);
                console.log('courseId:', this.courseId);
                console.log('Milestones API response:', response.data);
                
                if (response.data?.status === 'success') {
                    this.allMilestones = response.data.data?.data || [];
                    console.log('Loaded milestones:', this.allMilestones);
                } else {
                    console.warn('Milestones API returned non-success status:', response.data);
                    this.allMilestones = [];
                }
            } catch (error) {
                console.error('Error loading milestones:', error);
                this.allMilestones = [];
            }
        },
        
        async loadAllModules() {
            try {
                const response = await axios.get(`course-modules?course_id=${this.courseId}`);
                console.log('Modules API response:', response.data);
                if (response.data?.status === 'success') {
                    this.allModules = response.data.data?.data || [];
                    console.log('Loaded modules:', this.allModules);
                } else {
                    console.warn('Modules API returned non-success status:', response.data);
                    this.allModules = [];
                }
            } catch (error) {
                console.error('Error loading modules:', error);
                this.allModules = [];
            }
        },
        
        async loadAllClasses() {
            try {
                const response = await axios.get(`course-module-classes?course_id=${this.courseId}`);
                console.log('Classes API response:', response.data);
                if (response.data?.status === 'success') {
                    this.allClasses = response.data.data?.data || [];
                    console.log('Loaded classes:', this.allClasses);
                } else {
                    console.warn('Classes API returned non-success status:', response.data);
                    this.allClasses = [];
                }
            } catch (error) {
                console.error('Error loading classes:', error);
                this.allClasses = [];
            }
        },
        
        async loadAllQuizzes() {
            try {
                const response = await axios.get('quizzes');
                console.log('Quizzes API response:', response.data);
                if (response.data?.status === 'success') {
                    this.allQuizzes = response.data.data?.data || [];
                    console.log('Loaded quizzes:', this.allQuizzes);
                } else {
                    console.warn('Quizzes API returned non-success status:', response.data);
                    this.allQuizzes = [];
                }
            } catch (error) {
                console.error('Error loading quizzes:', error);
                this.allQuizzes = [];
            }
        },
        
        // Event handlers
        onFilterChange() {
            // Just trigger reactive updates - no need to clear selections
            console.log('Filter changed:', this.filterMilestone, this.filterModule);
        },

        // Helper methods for class and quiz checking
        isClassSelected(classId) {
            return this.selectedClassId === classId;
        },

        // Select a single class (radio button behavior)
        selectClass(classId) {
            if (this.selectedClassId !== classId) {
                // Clear existing assignments when switching classes
                this.assignments = [];
                this.selectedClassId = classId;
                console.log('Selected class:', classId);
            }
        },

        // Check if a quiz is assigned to the selected class
        isQuizAssignedToSelectedClass(quizId) {
            if (!this.selectedClassId) return false;
            return this.assignments.some(
                assignment => assignment.course_quiz_id === quizId && assignment.course_class_id === this.selectedClassId
            );
        },

        // Toggle quiz assignment for the selected class
        toggleQuizForSelectedClass(quizId, isAssigned) {
            if (!this.selectedClassId) return;

            if (isAssigned) {
                // Add assignment if not already exists
                if (!this.assignments.some(a => a.course_quiz_id === quizId && a.course_class_id === this.selectedClassId)) {
                    const classItem = this.allClasses.find(c => c.id === this.selectedClassId);
                    this.assignments.push({
                        course_id: this.courseId,
                        milestone_id: classItem.milestone_id,
                        course_module_id: classItem.course_modules_id,
                        course_module_class_id: this.selectedClassId,
                        quiz_id: quizId
                    });
                }
            } else {
                // Remove assignment
                this.assignments = this.assignments.filter(
                    assignment => !(assignment.course_quiz_id === quizId && assignment.course_class_id === this.selectedClassId)
                );
            }
            
            console.log('Current assignments:', this.assignments);
        },
        
        // Helper methods
        getMilestoneName(milestoneId) {
            const milestone = this.allMilestones.find(m => m.id === milestoneId);
            return milestone ? milestone.title : 'Unknown';
        },
        
        getModuleName(moduleId) {
            const module = this.allModules.find(m => m.id === moduleId);
            return module ? module.title : 'Unknown';
        },
        
        getClassQuizCount(classId) {
            return this.assignments.filter(
                assignment => assignment.course_class_id === classId
            ).length;
        },
        
        validateForm() {
            this.errors = {};
            
            if (!this.selectedClassId) {
                window.s_warning('Please select a class');
                return false;
            }
            
            if (this.assignments.length === 0) {
                window.s_warning('Please assign at least one quiz to the selected class');
                return false;
            }
            
            return true;
        },
        
        async submitForm() {
            if (!this.validateForm()) {
                return;
            }
            
            this.submitting = true;
            
            try {
                const payload = {
                    assignments: this.assignments
                };
                
                console.log('Submitting class quiz assignments:', payload);
                
                let response;
                if (this.isEditMode) {
                    const slug = this.$route.params.slug;
                    response = await axios.post(`course-module-class-quizzes/update/${slug}`, payload);
                } else {
                    response = await axios.post('course-module-class-quizzes/store', payload);
                }
                
                console.log('Submit - API Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert('Class quiz assignments saved successfully!');
                    this.goBack();
                } else {
                    window.s_error(response.data.message || 'Failed to save assignments');
                }
                
            } catch (error) {
                console.error('Error saving assignments:', error);
                
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    window.s_warning('Form has errors. Please correct them.');
                } else {
                    window.s_error('Failed to save assignments');
                }
            } finally {
                this.submitting = false;
            }
        },
        
        goBack() {
            this.$router.push({ 
                name: 'CourseQuizAll', 
                params: { id: this.$route.params.id } 
            });
        },

        async loadEditAssignments() {
            try {
                const slug = this.$route.params.slug;
                console.log('Loading edit assignments for slug:', slug);
                const response = await axios.get(`course-module-class-quizzes/${slug}`);
                console.log('Edit assignments API response:', response.data);
                
                if (response.data?.status === 'success') {
                    const assignments = response.data.data?.assignments || [];
                    console.log('Assignments found:', assignments);
                    
                    if (assignments.length) {
                        // Get the first class ID (since we only support single class selection now)
                        const classIds = [...new Set(assignments.map(a => a.course_class_id || a.course_module_class_id))];
                        this.selectedClassId = classIds[0]; // Select the first class
                        
                        // Set assignments array with correct field names
                        this.assignments = assignments.map(a => ({
                            course_id: a.course_id,
                            course_milestone_id: a.course_milestone_id || a.milestone_id,
                            course_module_id: a.course_module_id,
                            course_class_id: a.course_class_id || a.course_module_class_id,
                            course_quiz_id: a.course_quiz_id || a.quiz_id
                        }));
                        
                        console.log('Selected class ID:', this.selectedClassId);
                        console.log('Set assignments:', this.assignments);
                    } else {
                        console.log('No assignments found');
                    }
                } else {
                    console.error('API response not successful:', response.data);
                }
            } catch (error) {
                console.error('Error loading edit assignments:', error);
                window.s_error('Failed to load existing assignments');
            }
        },
    },
    
    async mounted() {
        // Ensure we have course details
        const courseSlug = this.$route.params.id;
        if (courseSlug && !this.currentCourse) {
            await this.getCourseDetails(courseSlug);
        }
        
        // Load initial form data
        await this.loadInitialData();
        
        // If in edit mode, load existing assignments
        if (this.isEditMode) {
            await this.loadEditAssignments();
        }
    }
};
</script>

<style scoped>
.course-class-quiz-form {
    position: relative;
    max-width: 100%;
}

.form-section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.filter-section {
    border-left-color: #28a745;
}

.class-quiz-section {
    padding: 0;
    border: none;
    border-radius: 0;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.section-title::before {
    content: '';
    width: 20px;
    height: 2px;
    background-color: #007bff;
    margin-right: 10px;
}

.classes-panel, .quizzes-panel {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    height: 600px;
    display: flex;
    flex-direction: column;
}

.panel-header {
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0;
}

.panel-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #495057;
}

.search-box {
    position: relative;
}

.search-box .search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.panel-content {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.class-item, .quiz-item {
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.class-item {
    cursor: pointer;
}

.class-item:hover {
    border-color: #007bff;
    /* background-color: #f8f9fa; */
}

.class-item.selected {
    border-color: #28a745;
}

.quiz-item {
    cursor: pointer;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: flex;
    justify-content: flex-start;
}

.quiz-item:hover {
    border-color: #007bff;
}

.quiz-item input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quiz-item.disabled {
    opacity: 0.6;
}

.no-class-selected {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    margin: 20px;
}

.no-class-selected i {
    opacity: 0.5;
    color: #dee2e6;
}

.selection-radio, .quiz-selection {
    margin-right: 10px;
    display: flex;
    align-items: flex-start;
    padding-top: 2px;
}

.form-check-input {
    width: 18px;
    height: 18px;
}

.milestone-badge, .module-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
    margin-right: 5px;
}

.milestone-badge {
    background-color: #e3f2fd;
    color: #1976d2;
}

.module-badge {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.quiz-actions {
    min-width: 200px;
    margin-left: 15px;
}

.class-info, .quiz-info {
    flex: 1;
}

.class-name, .quiz-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: #495057;
}

.class-description, .quiz-description {
    font-size: 0.8rem;
    color: #6c757d;
    margin: 0;
}

.class-actions {
    display: flex;
    align-items: center;
}

.quiz-count {
    background-color: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.quiz-actions {
    min-width: 150px;
    margin-left: 15px;
}

.quiz-actions select {
    font-size: 0.85rem;
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-data i {
    opacity: 0.5;
    color: #dee2e6;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control-sm {
    padding: 6px 8px;
    font-size: 0.875rem;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 5px;
    font-size: 0.875rem;
    color: #dc3545;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    font-weight: 500;
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
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-section {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .classes-panel, .quizzes-panel {
        height: 400px;
        margin-bottom: 20px;
    }
    
    .class-item, .quiz-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
    }
    
    .quiz-actions {
        margin-left: 0;
        margin-top: 10px;
        width: 100%;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .section-title {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .form-section {
        padding: 10px;
    }
    
    .classes-panel, .quizzes-panel {
        height: 350px;
    }
    
    .panel-header {
        padding: 10px 15px;
    }
    
    .section-title::before {
        width: 15px;
        margin-right: 8px;
    }
}
</style>
