<template>
    <div class="course-class-all">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Course Classes
                </h5>
                <button @click="createNewClass" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Add Class
                </button>
            </div>
            <div class="card-body">

                <!-- Filter Controls -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="filter-milestone">Filter by Milestone</label>
                        <select id="filter-milestone" v-model="selectedMilestoneId" class="form-control">
                            <option value="">All Milestones</option>
                            <option v-for="milestone in milestones" :key="milestone.id" :value="milestone.id">
                                {{ milestone.title }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filter-module">Filter by Module</label>
                        <select id="filter-module" v-model="selectedModuleId" class="form-control"
                            :disabled="!selectedMilestoneId">
                            <option value="">All Modules</option>
                            <option
                                v-for="module in modules.filter(m => String(m.milestone_id) === String(selectedMilestoneId))"
                                :key="module.id" :value="module.id">
                                {{ module.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Classes List -->
                <div v-else-if="filteredClasses.length > 0" class="classes-list">
                    <div v-for="(classItem, index) in filteredClasses" :key="classItem.id || index" class="class-item">
                        <div class="class-header">
                            <div class="class-info">
                                <div class="class-video">
                                    <div v-if="classItem.class_video_poster" class="video-thumbnail">
                                        <img :src="`/${classItem.class_video_poster}`" alt="Video thumbnail">
                                        <div class="play-overlay">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                    <div v-else class="video-placeholder">
                                        <i class="fas fa-video fa-2x"></i>
                                    </div>
                                </div>
                                <div class="class-details">
                                    <h6 class="class-title">{{ classItem.title }}</h6>
                                    <p class="class-meta">
                                        <span class="class-type" :class="'type-' + classItem.type">
                                            <i
                                                :class="classItem.type === 'live' ? 'fas fa-broadcast-tower' : 'fas fa-video'"></i>
                                            {{ classItem.type }}
                                        </span>
                                        <span class="class-duration" v-if="classItem.duration">
                                            <i class="fas fa-clock"></i>
                                            {{ classItem.duration }} min
                                        </span>
                                    </p>
                                    <p class="class-module" v-if="classItem.module">
                                        <small class="text-muted">
                                            Module: {{ classItem.module.title }}
                                            <span v-if="classItem.milestone"> | Milestone: {{ classItem.milestone.title
                                            }}</span>
                                        </small>
                                    </p>
                                    <p class="class-number" v-if="classItem.class_no">
                                        <small class="text-muted">
                                            Class #{{ classItem.class_no }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="class-actions">
                                <span :class="'badge badge-' + (classItem.status === 'active' ? 'success' : 'danger')">
                                    {{ classItem.status }}
                                </span>
                                <button @click="editClass(classItem)" class="btn btn-outline-primary btn-sm ml-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteClass(classItem.id, index)"
                                    class="btn btn-outline-danger btn-sm ml-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No classes found</h5>
                    <p class="text-muted">Create your first class to get started.</p>
                    <button @click="createNewClass" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Create First Class
                    </button>
                </div>
            </div>
        </div>

        <!-- Class Form Modal -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ isEditing ? 'Edit Class' : 'Create New Class' }}
                        </h5>
                        <button @click="closeModal" type="button" class="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveClass">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="milestone_id">Milestone *</label>
                                        <select id="milestone_id" v-model="currentClass.milestone_id"
                                            @change="filterModulesByMilestone" class="form-control" required>
                                            <option value="">Select Milestone</option>
                                            <option v-for="milestone in milestones" :key="milestone.id"
                                                :value="milestone.id">
                                                {{ milestone.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_modules_id">Module *</label>
                                        <select id="course_modules_id" v-model="currentClass.course_modules_id"
                                            class="form-control" required :disabled="!currentClass.milestone_id">
                                            <option value="">Select Module</option>
                                            <option v-for="module in filteredModules" :key="module.id"
                                                :value="module.id">
                                                {{ module.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title">Class Title *</label>
                                <input type="text" id="title" v-model="currentClass.title" class="form-control"
                                    required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Class Type</label>
                                        <select id="type" v-model="currentClass.type" class="form-control">
                                            <option value="live">Live</option>
                                            <option value="recorded">Recorded</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_no">Class Number</label>
                                        <input type="text" id="class_no" v-model="currentClass.class_no"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="class_video_link">Video Link</label>
                                <input type="url" id="class_video_link" v-model="currentClass.class_video_link"
                                    class="form-control" placeholder="https://...">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_video_poster">Video Poster Image</label>
                                        <div class="image-upload-container">
                                            <div v-if="imagePosterPreview" class="current-image">
                                                <img :src="imagePosterPreview" alt="Video Poster" class="img-fluid rounded">
                                                <button type="button" @click="removePosterImage" class="btn btn-sm btn-danger remove-image-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div v-else class="upload-placeholder" @click="$refs.posterInput.click()">
                                                <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                                <p>Upload Image</p>
                                                <small>JPG, PNG, GIF (Max 2MB)</small>
                                            </div>
                                            <input ref="posterInput" type="file" id="class_video_poster" @change="handlePosterUpload" class="d-none" accept="image/*">
                                        </div>
                                        <small class="text-muted" v-if="currentClass.class_video_poster && !imagePosterPreview">
                                            Current: {{ currentClass.class_video_poster }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" v-model="currentClass.status" class="form-control">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-secondary">Cancel</button>
                        <button @click="saveClass" type="button" class="btn btn-primary" :disabled="submitting">
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            {{ submitting ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseClassAll',

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),

        filteredModules() {
            if (!this.currentClass.milestone_id) {
                return [];
            }
            return this.modules.filter(module => module.milestone_id == this.currentClass.milestone_id);
        },

        filteredClasses() {
            return this.classes.filter(classItem => {
                const milestoneMatch = this.selectedMilestoneId ? String(classItem.milestone_id) === String(this.selectedMilestoneId) : true;
                const moduleMatch = this.selectedModuleId ? String(classItem.course_modules_id) === String(this.selectedModuleId) : true;
                return milestoneMatch && moduleMatch;
            });
        }
    },

    data() {
        return {
            loading: false,
            classes: [],
            modules: [],
            milestones: [],
            showModal: false,
            isEditing: false,
            submitting: false,
            selectedPosterFile: null,
            selectedMilestoneId: '',
            selectedModuleId: '',
            imagePosterPreview: null,
            currentClass: {
                course_id: '',
                milestone_id: '',
                course_modules_id: '',
                class_no: '',
                title: '',
                type: 'recorded',
                class_video_link: '',
                class_video_poster: '',
                status: 'active'
            }
        };
    },

    async created() {
        await this.loadData();
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),

        async loadData() {
            await Promise.all([
                this.loadClasses(),
                this.loadModules(),
                this.loadMilestones()
            ]);
        },

        async loadClasses() {
            this.loading = true;
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-module-classes?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.classes = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading classes:', error);
                window.s_error('Failed to load classes');
            } finally {
                this.loading = false;
            }
        },

        async loadModules() {
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-modules?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.modules = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading modules:', error);
            }
        },

        async loadMilestones() {
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-milestones?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.milestones = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading milestones:', error);
            }
        },

        filterModulesByMilestone() {
            // Reset module selection when milestone changes
            this.currentClass.course_modules_id = '';
        },

        handlePosterUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate size (2MB) and type
                if (file.size > 2 * 1024 * 1024) {
                    window.s_alert('Image size must be less than 2MB', 'error');
                    return;
                }
                if (!file.type.startsWith('image/')) {
                    window.s_alert('Only image files are allowed', 'error');
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePosterPreview = e.target.result;
                };
                reader.readAsDataURL(file);

                this.selectedPosterFile = file;
            }
        },

        createNewClass() {
            this.isEditing = false;
            this.selectedPosterFile = null;
            const store = useCourseDetailsStore();
            this.currentClass = {
                course_id: store.currentCourse?.id || '',
                milestone_id: '',
                course_modules_id: '',
                class_no: '',
                title: '',
                type: 'recorded',
                class_video_link: '',
                class_video_poster: '',
                status: 'active'
            };
            this.imagePosterPreview = null;
            if (this.$refs.posterInput) this.$refs.posterInput.value = '';
            this.showModal = true;
        },

        editClass(classItem) {
            this.isEditing = true;
            this.currentClass = { ...classItem };
            // Set preview for existing poster
            if (classItem.class_video_poster) {
                if (classItem.class_video_poster.startsWith('http')) {
                    this.imagePosterPreview = classItem.class_video_poster;
                } else {
                    this.imagePosterPreview = `${window.location.origin}/${classItem.class_video_poster}`;
                }
            } else {
                this.imagePosterPreview = null;
            }

            if (this.$refs.posterInput) this.$refs.posterInput.value = '';
            this.showModal = true;
        },

        async saveClass() {
            if (!this.currentClass.title.trim()) {
                window.s_warning('Please enter a title');
                return;
            }

            if (!this.currentClass.course_modules_id) {
                window.s_warning('Please select a module');
                return;
            }

            this.submitting = true;
            try {
                const store = useCourseDetailsStore();

                // Create FormData for file upload
                const formData = new FormData();
                formData.append('course_id', store.currentCourse?.id || '');
                formData.append('milestone_id', this.currentClass.milestone_id || '');
                formData.append('course_modules_id', this.currentClass.course_modules_id || '');
                formData.append('class_no', this.currentClass.class_no || '');
                formData.append('title', this.currentClass.title || '');
                formData.append('type', this.currentClass.type || '');
                formData.append('class_video_link', this.currentClass.class_video_link || '');
                formData.append('status', this.currentClass.status || '');

                // Add the poster file if selected
                if (this.selectedPosterFile) {
                    formData.append('class_video_poster', this.selectedPosterFile);
                } else if (this.currentClass.class_video_poster && this.isEditing) {
                    formData.append('existing_poster', this.currentClass.class_video_poster);
                }

                let response;
                if (this.isEditing) {
                    response = await axios.post(`course-module-classes/update/${this.currentClass.slug}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {
                    response = await axios.post('course-module-classes/store', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                }

                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditing ? 'Class updated successfully!' : 'Class created successfully!');
                    this.closeModal();
                    await this.loadClasses();
                } else {
                    window.s_error('Failed to save class');
                }
            } catch (error) {
                console.error('Error saving class:', error);
                window.s_error('Failed to save class');
            } finally {
                this.submitting = false;
            }
        },

        async deleteClass(id, index) {
            if (!confirm('Are you sure you want to delete this class?')) {
                return;
            }

            try {
                if (id) {
                    const classItem = this.classes[index];
                    await axios.post(`course-module-classes/destroy/${classItem.slug}`);
                }
                this.classes.splice(index, 1);
                window.s_alert('Class deleted successfully!');
            } catch (error) {
                console.error('Error deleting class:', error);
                window.s_error('Failed to delete class');
            }
        },

        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.selectedPosterFile = null;
            this.imagePosterPreview = null;
            this.currentClass = {
                course_id: '',
                milestone_id: '',
                course_modules_id: '',
                class_no: '',
                title: '',
                type: 'recorded',
                class_video_link: '',
                class_video_poster: '',
                status: 'active'
            };

            // Reset file input
            const fileInput = document.getElementById('class_video_poster');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    }
};
</script>

<style scoped>
.course-class-all {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.classes-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.class-item {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    transition: box-shadow 0.15s ease-in-out;
}

.class-item:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.class-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.class-info {
    display: flex;
    gap: 1rem;
    flex: 1;
}

.class-video {
    flex-shrink: 0;
}

.video-thumbnail {
    position: relative;
    width: 120px;
    height: 68px;
    border-radius: 0.375rem;
    overflow: hidden;
}

.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    border-radius: 50%;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-placeholder {
    width: 120px;
    height: 68px;
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.class-details h6 {
    margin: 0 0 0.5rem 0;
    color: #495057;
}

.class-meta {
    margin: 0.5rem 0;
    display: flex;
    gap: 1rem;
    align-items: center;
}

.class-type {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.type-live {
    background-color: #dc3545;
    color: white;
}

.type-recorded {
    background-color: #28a745;
    color: white;
}

.class-duration {
    color: #6c757d;
    font-size: 0.875rem;
}

.class-actions {
    display: flex;
    align-items: center;
}

.empty-state {
    padding: 3rem 1rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-success {
    background-color: #28a745;
}

.badge-secondary {
    background-color: #6c757d;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.modal {
    z-index: 1050;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

@media (max-width: 768px) {
    .class-header {
        flex-direction: column;
        gap: 1rem;
    }

    .class-info {
        flex-direction: column;
    }

    .class-actions {
        width: 100%;
        justify-content: space-between;
    }
}

/* Image upload styles - copied from CreateCourse.vue for exact parity */
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
</style>
