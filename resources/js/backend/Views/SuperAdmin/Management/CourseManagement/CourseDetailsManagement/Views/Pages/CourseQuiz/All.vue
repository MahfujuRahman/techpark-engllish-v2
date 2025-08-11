<template>
    <div class="course-class-quiz-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Class Quiz List</h6>
                    </div>
                    <div>
                        <router-link :to="{ name: 'CourseQuizCreate', params: { id: $route.params.id } }"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Class Quiz
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Course</th>
                                <th>Milestone</th>
                                <th>Module</th>
                                <th>Class</th>
                                <th>Quiz</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody v-if="classQuizzes?.length">
                            <tr v-for="(quiz, index) in classQuizzes" :key="quiz.id">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="quiz-title">
                                        {{ quiz.course_id }}
                                    </div>
                                </td>
                                <td>
                                    <div class="class-name">
                                        {{ quiz.milestone_id || '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="start-date">
                                        {{ quiz.course_module_id || '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="end-date">
                                        {{ quiz.course_module_class_id || '-' }}
                                    </div>
                                </td>
                                <td class="question-count">
                                    {{ quiz.quiz_id || '-' }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link
                                            :to="{ name: 'CourseQuizDetails', params: { id: $route.params.id, slug: quiz.slug } }"
                                            class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <router-link
                                            :to="{ name: 'CourseQuizEdit', params: { id: $route.params.id, slug: quiz.slug } }"
                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deleteClassQuiz(quiz)" class="btn btn-sm btn-outline-danger"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="no-data-container">
                                        <div class="no-data-icon">
                                            <i class="fas fa-chalkboard-teacher fa-3x"></i>
                                        </div>
                                        <div class="no-data-text">
                                            <h6>No class quizzes found</h6>
                                            <p>Create a new class quiz for this course.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    name: 'CourseClassQuizAll',

    data() {
        return {
            classQuizzes: [],
        };
    },

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse', 'loading']),
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['clearMessages']),

        async getCourseClassQuizzes() {
            const courseSlug = this.$route.params.id;
            if (!courseSlug) return;

            try {
                // First ensure we have the current course loaded
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                console.log('Fetching quiz for course ID:', courseId);
                const response = await axios.get(`course-module-class-quizzes?course_id=${courseId}`);

                console.log('API Response:', response.data);

                // Check if response has the expected structure
                if (response.data && response.data.status === 'success') {
                    this.classQuizzes = response.data.data?.data || [];
                    console.log('Loaded class quizzes:', this.classQuizzes);
                } else {
                    console.error('Unexpected response structure:', response.data);
                    this.classQuizzes = [];
                }
            } catch (error) {
                console.error('Error fetching class quizzes:', error);
                this.classQuizzes = [];
            }
        },

        async deleteClassQuiz(quiz) {
            const confirmed = await window.s_confirm('Are you sure you want to delete this class quiz?', 'Yes, delete it!');
            if (confirmed) {
                try {
                    const quizSlug = quiz.slug; // Use slug from the quiz data
                    console.log('Deleting class quiz with slug:', quizSlug);
                    await axios.post(`course-class-quizzes/destroy/${quizSlug}`);
                    window.s_alert('Class quiz deleted successfully!');
                    await this.getCourseClassQuizzes();
                } catch (error) {
                    console.error('Error deleting class quiz:', error);
                    window.s_error('Failed to delete class quiz!');
                }
            }
        },
    },

    async mounted() {
        await this.getCourseClassQuizzes();
    }
};
</script>

<style scoped>
.course-class-quiz-all {
    position: relative;
}

.no-data-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-data-icon {
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-data-text h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.no-data-text p {
    margin: 0;
    font-size: 0.9rem;
}

.quiz-title {
    min-width: 120px;
}

.quiz-title strong {
    color: #495057;
}

.class-name {
    min-width: 100px;
}

.question-count {
    text-align: center;
    font-weight: 500;
}

.price-info {
    text-align: center;
}

.original-price {
    font-weight: 500;
}

.discount-badge {
    background-color: #dc3545;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    margin-top: 2px;
}

.booking-info {
    min-width: 100px;
}

.booking-numbers {
    font-weight: 500;
    text-align: center;
}

.booking-percentage {
    text-align: center;
    margin-top: 2px;
}

.time-info {
    text-align: center;
    font-size: 0.85rem;
}

.btn-group .btn {
    margin-right: 2px;
    padding: 4px 8px;
}

.btn-group .btn:last-child {
    margin-right: 0;
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

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
    font-size: 0.85rem;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.table td {
    font-size: 0.85rem;
    vertical-align: middle;
    padding: 12px 8px;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.3s ease;
}

/* Responsive */
@media (max-width: 1200px) {
    .table-responsive {
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 8px 6px;
    }
}

@media (max-width: 992px) {
    .btn-group {
        flex-direction: column;
    }

    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
        font-size: 0.75rem;
    }

    .time-info,
    .booking-info,
    .price-info {
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.7rem;
    }

    .table th,
    .table td {
        padding: 6px 4px;
    }

    .batch-name {
        min-width: 80px;
    }

    .booking-info {
        min-width: 70px;
    }

    .btn-group .btn {
        padding: 2px 4px;
    }

    .btn-group .btn i {
        font-size: 0.7rem;
    }
}

/* Table scrolling for mobile */
@media (max-width: 576px) {
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }
}
</style>
