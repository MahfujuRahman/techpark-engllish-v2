<template>
    <div class="course-instructor-management">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            Course Instructor Management
                        </h5>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" @click="openCreateModal" :disabled="!course_id">
                            <i class="fas fa-plus mr-1"></i>
                            Add New Instructor
                        </button>
                        <!-- Debug info -->
                        <small class="text-muted ml-2" v-if="!course_id">
                            Course ID: {{ course_id || 'Not found' }} |
                            Store Course: {{ currentCourse?.id || 'None' }} |
                            Route: {{ $route.params.id || 'None' }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <div class="filters mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Select Batch</label>
                            <select v-model="selectedBatch" class="form-control" @change="loadInstructors">
                                <option value="">All Batches</option>
                                <option v-for="batch in batches" :key="batch.id" :value="batch.id">
                                    {{ batch.batch_name || batch.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select v-model="selectedStatus" class="form-control" @change="loadInstructors">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input v-model="searchTerm" type="text" class="form-control"
                                    placeholder="Instructor or course name..." @input="debounceSearch">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm" @click="exportInstructors" :disabled="loading">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-info btn-sm" @click="openImportModal">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Instructors List -->
                <div v-else-if="instructors.length > 0" class="instructors-list">
                    <div v-for="instructor in instructors" :key="instructor.id" class="instructor-card">
                        <div class="instructor-header">
                            <div class="instructor-info">
                                <div class="instructor-avatar">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="instructor-details">
                                    <h6 class="instructor-name">{{ instructor.instructor?.full_name ||
                                        instructor.instructor?.name || 'N/A' }}</h6>
                                    <div class="instructor-meta">
                                        <span class="instructor-batch">
                                            <i class="fas fa-layer-group"></i>
                                            {{ instructor.batch?.batch_name || instructor.batch?.title || 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="instructor-course">
                                        <i class="fas fa-book"></i>
                                        <strong>Course:</strong> {{ instructor.course?.title || 'N/A' }}
                                    </div>
                                    <div class="instructor-dates">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-plus"></i>
                                            {{ formatDate(instructor.created_at) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="instructor-actions">
                                <span :class="['badge', getBadgeClass(instructor.status)]">
                                    {{ getStatusText(instructor.status) }}
                                </span>
                                <div class="action-buttons">
                                    <button class="btn btn-outline-success btn-sm" @click="toggleStatus(instructor)"
                                        :title="instructor.status === 'active' ? 'Deactivate' : 'Activate'"
                                        :disabled="statusToggling === instructor.slug">
                                        <i class="fas fa-spinner fa-spin" v-if="statusToggling === instructor.slug"></i>
                                        <i v-else
                                            :class="instructor.status === 'active' ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" @click="editInstructor(instructor)"
                                        :title="'Edit'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" @click="viewInstructor(instructor)"
                                        :title="'View Details'">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" @click="removeInstructor(instructor)"
                                        :title="'Delete'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button v-if="instructor.deleted_at" class="btn btn-outline-warning btn-sm"
                                        @click="restoreInstructor(instructor)" :title="'Restore'"
                                        :disabled="restoring === instructor.slug">
                                        <i class="fas fa-spinner fa-spin" v-if="restoring === instructor.slug"></i>
                                        <i v-else class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="pagination.total > pagination.per_page" class="pagination-wrapper mt-4">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                    <button class="page-link" @click="changePage(pagination.current_page - 1)"
                                        :disabled="pagination.current_page === 1">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                </li>
                                <li v-for="page in getVisiblePages()" :key="page" class="page-item"
                                    :class="{ active: page === pagination.current_page }">
                                    <button class="page-link" @click="changePage(page)">{{ page }}</button>
                                </li>
                                <li class="page-item"
                                    :class="{ disabled: pagination.current_page === pagination.last_page }">
                                    <button class="page-link" @click="changePage(pagination.current_page + 1)"
                                        :disabled="pagination.current_page === pagination.last_page">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                        <div class="pagination-info text-center mt-2">
                            <small class="text-muted">
                                {{ pagination.from }}-{{ pagination.to }} of {{ pagination.total }} entries
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Instructors Found</h5>
                    <p class="text-muted">No instructors have been added to this course yet.</p>
                    <button class="btn btn-primary" @click="openCreateModal" :disabled="!course_id">
                        <i class="fas fa-plus mr-1"></i>
                        Add First Instructor
                    </button>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div class="modal fade" id="instructorModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            {{ editingInstructor ? 'Edit Instructor' : 'Add New Instructor' }}
                        </h5>
                        <button type="button" class="close" @click="closeModal('instructorModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveInstructor">
                            <div class="form-group mb-3">
                                <label class="form-label">
                                    Select Instructor <span class="text-danger">*</span>
                                </label>
                                <select v-model="formData.instructor_id" class="form-control" required>
                                    <option value="">Choose Instructor</option>
                                    <option v-for="user in availableInstructors" :key="user.id" :value="user.id">
                                        {{ user.full_name || user.first_name }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">
                                    Select Batch <span class="text-danger">*</span>
                                </label>
                                <select v-model="formData.batch_id" class="form-control" required>
                                    <option value="">Choose Batch</option>
                                    <option v-for="batch in batches" :key="batch.id" :value="batch.id">
                                        {{ batch.batch_name || batch.title }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Status</label>
                                <select v-model="formData.status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group text-right">
                                <button type="button" class="btn btn-secondary mr-2"
                                    @click="closeModal('instructorModal')" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" :disabled="saving">
                                    <i class="fas fa-spinner fa-spin mr-1" v-if="saving"></i>
                                    {{ saving ? 'Saving...' : (editingInstructor ? 'Update' : 'Save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Confirm Delete
                        </h5>
                        <button type="button" class="close" @click="closeModal('deleteModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this instructor?</p>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal('deleteModal')"
                            data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="deleting">
                            <i class="fas fa-spinner fa-spin mr-1" v-if="deleting"></i>
                            {{ deleting ? 'Deleting...' : 'Yes, Delete' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-eye mr-2"></i>
                            Instructor Details
                        </h5>
                        <button type="button" class="close" @click="closeModal('viewModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="viewingInstructor">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Instructor Name:</label>
                                    <p>{{ viewingInstructor.instructor?.full_name || viewingInstructor.instructor?.name
                                        || 'N/A' }}</p>
                                </div>
                                <div class="info-group">
                                    <label>Status:</label>
                                    <p>
                                        <span :class="['badge', getBadgeClass(viewingInstructor.status)]">
                                            {{ getStatusText(viewingInstructor.status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <label>Batch:</label>
                                    <p>{{ viewingInstructor.batch?.batch_name || viewingInstructor.batch?.title || 'N/A'
                                        }}</p>
                                </div>

                                <div class="info-group">
                                    <label>Created Date:</label>
                                    <p>{{ formatDate(viewingInstructor.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-upload mr-2"></i>
                            Import Instructors
                        </h5>
                        <button type="button" class="close" @click="closeModal('importModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="importInstructors">
                            <div class="form-group mb-3">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" ref="importFile" class="form-control" accept=".xlsx,.xls,.csv"
                                    required>
                                <small class="form-text text-muted">
                                    Only .xlsx, .xls, or .csv files are supported
                                </small>
                            </div>

                            <div class="alert alert-info">
                                <strong>Note:</strong> The file should contain instructor_id, course_id, batch_id,
                                status columns.
                            </div>

                            <div class="form-group text-right">
                                <button type="button" class="btn btn-secondary mr-2" @click="closeModal('importModal')"
                                    data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-success" :disabled="importing">
                                    <i class="fas fa-spinner fa-spin mr-1" v-if="importing"></i>
                                    {{ importing ? 'Importing...' : 'Import' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'pinia';
import axios from 'axios';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseInstructor',

    setup() {
        const courseDetailsStore = useCourseDetailsStore();
        return {
            courseDetailsStore,
        };
    },

    data() {
        return {
            loading: false,
            saving: false,
            deleting: false,
            importing: false,
            statusToggling: null,
            restoring: null,

            instructors: [],
            batches: [],
            availableInstructors: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
                from: 0,
                to: 0
            },

            // Filters
            selectedBatch: '',
            selectedStatus: '',
            searchTerm: '',
            searchTimeout: null,

            // Modal states
            editingInstructor: null,
            instructorToDelete: null,
            viewingInstructor: null,

            // Form data
            formData: {
                instructor_id: '',
                batch_id: '',
                status: 'active',
            },
        };
    },

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),

        course_id() {
            // Get course ID from the store's current course using proper mapState
            return this.currentCourse?.id;
        },
    },

    methods: {
        // Debug method to check course_id
        checkCourseId() {
            console.log('=== Course ID Debug Info ===');
            console.log('Computed course_id:', this.course_id);
            console.log('currentCourse (mapState):', this.currentCourse);
            console.log('Store currentCourse:', this.courseDetailsStore.currentCourse);
            console.log('Route params:', this.$route.params);
        },
        async loadInstructors(page = 1) {
            if (!this.course_id) return;

            this.loading = true;
            try {
                const params = {
                    course_id: this.course_id,
                    page: page,
                    limit: this.pagination.per_page,
                };

                if (this.selectedBatch) {
                    params.batch_id = this.selectedBatch;
                }

                if (this.selectedStatus) {
                    params.status = this.selectedStatus;
                }

                if (this.searchTerm) {
                    params.search = this.searchTerm;
                }

                const response = await axios.get('/course-course-instructors', {
                    params: params
                });

                if (response.data.status === 'success') {
                    // The API returns data in response.data.data.data (nested structure)
                    this.instructors = response.data.data.data || [];

                    // Handle pagination - the pagination info is in response.data.data
                    const paginationData = response.data.data;
                    this.pagination = {
                        current_page: paginationData.current_page,
                        last_page: paginationData.last_page,
                        per_page: paginationData.per_page,
                        total: paginationData.total,
                        from: paginationData.from,
                        to: paginationData.to
                    };

                    console.log('Loaded instructors:', this.instructors);
                    console.log('Pagination:', this.pagination);
                } else {
                    console.error('Instructors failed to load');
                    window.s_error && window.s_error('Instructors failed to load');
                }
            } catch (error) {
                console.error('Error loading instructors:', error);
                window.s_error && window.s_error('Instructors failed to load');
                this.instructors = [];
            }
            this.loading = false;
        },

        async loadBatches() {
            if (!this.course_id) return;

            try {
                const response = await axios.get('/course-batches', {
                    params: {
                        course_id: this.course_id,
                        get_all: 1,
                        status: 'active'
                    }
                });

                if (response.data.status === 'success') {
                    this.batches = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading batches:', error);
                this.batches = [];
            }
        },

        async loadAvailableInstructors() {
            try {
                const response = await axios.get('/course-instructors', {
                    params: {
                        get_all: 1,
                        role: 'instructor', // Assuming you have role filtering
                        status: 'active'
                    }
                });

                if (response.data.status === 'success') {
                    this.availableInstructors = response.data.data || [];
                } else {
                    // Fallback to all users if role filtering not available
                    const allUsersResponse = await axios.get('/course-instructors', {
                        params: {
                            get_all: 1,
                            status: 'active'
                        }
                    });
                    this.availableInstructors = allUsersResponse.data.data || [];
                }
            } catch (error) {
                console.error('Error loading instructors:', error);
                this.availableInstructors = [];
            }
        },

        debounceSearch() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            this.searchTimeout = setTimeout(() => {
                this.loadInstructors();
            }, 500);
        },

        // Modal control methods
        closeModal(modalId) {
            $(`#${modalId}`).modal('hide');
        },

        openCreateModal() {
            this.editingInstructor = null;
            this.formData = {
                instructor_id: '',
                batch_id: '',
                status: 'active',
            };

            $('#instructorModal').modal('show');
        },

        editInstructor(instructor) {
            this.editingInstructor = instructor;
            this.formData = {
                instructor_id: instructor.instructor_id,
                batch_id: instructor.batch_id,
                status: instructor.status,
            };

            $('#instructorModal').modal('show');
        },

        async saveInstructor() {
            if (!this.course_id) {
                window.s_error && window.s_error('Course ID not found');
                return;
            }

            this.saving = true;
            try {
                const formData = {
                    course_id: this.course_id,
                    instructor_id: this.formData.instructor_id,
                    batch_id: this.formData.batch_id,
                    status: this.formData.status,
                };

                let response;
                if (this.editingInstructor) {
                    response = await axios.post(
                        `/course-course-instructors/update/${this.editingInstructor.slug}`,
                        formData
                    );
                } else {
                    response = await axios.post('/course-course-instructors/store', formData);
                }

                if (response.data.status === 'success') {
                    window.s_success && window.s_success(this.editingInstructor ? 'Instructor updated successfully' : 'Instructor added successfully');
                    $('#instructorModal').modal('hide');
                    this.loadInstructors();
                } else {
                    window.s_error && window.s_error(response.data.message || 'Failed to save instructor');
                }
            } catch (error) {
                console.error('Error saving instructor:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = Object.values(error.response.data.errors).flat();
                    errors.forEach(error => window.s_error && window.s_error(error));
                } else {
                    window.s_error && window.s_error('Failed to save instructor');
                }
            }
            this.saving = false;
        },

        removeInstructor(instructor) {
            this.instructorToDelete = instructor;
            $('#deleteModal').modal('show');
        },

        async confirmDelete() {
            if (!this.instructorToDelete) return;

            this.deleting = true;
            try {
                const response = await axios.post('/course-course-instructors/soft-delete', {
                    slug: this.instructorToDelete.slug
                });

                if (response.data.status === 'success') {
                    window.s_success && window.s_success('Instructor deleted successfully');
                    $('#deleteModal').modal('hide');
                    this.loadInstructors();
                    this.instructorToDelete = null;
                } else {
                    window.s_error && window.s_error('Failed to delete instructor');
                }
            } catch (error) {
                console.error('Error deleting instructor:', error);
                window.s_error && window.s_error('Failed to delete instructor');
            }
            this.deleting = false;
        },

        // Pagination methods
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadInstructors(page);
            }
        },

        getVisiblePages() {
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            const delta = 2;
            const range = [];
            const rangeWithDots = [];

            for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
                range.push(i);
            }

            if (current - delta > 2) {
                rangeWithDots.push(1, '...');
            } else {
                rangeWithDots.push(1);
            }

            rangeWithDots.push(...range);

            if (current + delta < last - 1) {
                rangeWithDots.push('...', last);
            } else {
                rangeWithDots.push(last);
            }

            return rangeWithDots.filter((item, index, array) => array.indexOf(item) === index);
        },

        // Status and view methods
        getBadgeClass(status) {
            switch (status) {
                case 'active': return 'badge-success';
                case 'inactive': return 'badge-secondary';
                default: return 'badge-secondary';
            }
        },

        getStatusText(status) {
            switch (status) {
                case 'active': return 'Active';
                case 'inactive': return 'Inactive';
                default: return 'Unknown';
            }
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        // Status toggle
        async toggleStatus(instructor) {
            this.statusToggling = instructor.slug;
            try {
                const newStatus = instructor.status === 'active' ? 'inactive' : 'active';
                const response = await axios.post('/course-course-instructors/update-status', {
                    slug: instructor.slug,
                    status: newStatus
                });

                if (response.data.status === 'success') {
                    instructor.status = newStatus;
                    window.s_success && window.s_success('Status updated successfully');
                } else {
                    window.s_error && window.s_error('Failed to update status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                window.s_error && window.s_error('Failed to update status');
            }
            this.statusToggling = null;
        },

        // View instructor
        viewInstructor(instructor) {
            this.viewingInstructor = instructor;
            $('#viewModal').modal('show');
        },

        // Restore instructor
        async restoreInstructor(instructor) {
            this.restoring = instructor.slug;
            try {
                const response = await axios.post('/course-course-instructors/restore', {
                    slug: instructor.slug
                });

                if (response.data.status === 'success') {
                    window.s_success && window.s_success('Instructor restored successfully');
                    this.loadInstructors();
                } else {
                    window.s_error && window.s_error('Failed to restore instructor');
                }
            } catch (error) {
                console.error('Error restoring instructor:', error);
                window.s_error && window.s_error('Failed to restore instructor');
            }
            this.restoring = null;
        },

        // Export functionality
        async exportInstructors() {
            try {
                const params = {
                    course_id: this.course_id,
                    export: 1
                };

                if (this.selectedBatch) {
                    params.batch_id = this.selectedBatch;
                }

                if (this.selectedStatus) {
                    params.status = this.selectedStatus;
                }

                const response = await axios.get('/course-course-instructors', {
                    params: params,
                    responseType: 'blob'
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `course-instructors-${Date.now()}.xlsx`);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);

                window.s_success && window.s_success('Export successful');
            } catch (error) {
                console.error('Error exporting:', error);
                window.s_error && window.s_error('Export failed');
            }
        },

        // Import functionality
        openImportModal() {
            $('#importModal').modal('show');
        },

        async importInstructors() {
            const file = this.$refs.importFile.files[0];
            if (!file) return;

            this.importing = true;
            try {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('course_id', this.course_id);

                const response = await axios.post('/course-course-instructors/import', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.status === 'success') {
                    window.s_success && window.s_success('Import successful');
                    $('#importModal').modal('hide');
                    this.loadInstructors();
                    this.$refs.importFile.value = '';
                } else {
                    window.s_error && window.s_error(response.data.message || 'Import failed');
                }
            } catch (error) {
                console.error('Error importing:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = Object.values(error.response.data.errors).flat();
                    errors.forEach(error => window.s_error && window.s_error(error));
                } else {
                    window.s_error && window.s_error('Import failed');
                }
            }
            this.importing = false;
        },
    },

    async mounted() {
        console.log('CourseInstructor component mounted');

        // Ensure we have course data
        const courseSlug = this.$route.params.id;
        if (courseSlug && !this.courseDetailsStore.currentCourse) {
            try {
                await this.courseDetailsStore.getCourseDetails(courseSlug);
            } catch (error) {
                console.error('Error loading course details:', error);
            }
        }

        // Debug course_id availability
        this.checkCourseId();

        if (this.course_id) {
            await this.loadBatches();
            await this.loadAvailableInstructors();
            await this.loadInstructors();
        } else {
            console.warn('Course ID not available. Button will remain disabled.');
        }

        // Ensure modal close buttons work
        this.$nextTick(() => {
            // Re-initialize modals with proper jQuery
            $('#instructorModal').modal({
                backdrop: true,
                keyboard: true,
                show: false
            });

            $('#viewModal').modal({
                backdrop: true,
                keyboard: true,
                show: false
            });

            $('#deleteModal').modal({
                backdrop: true,
                keyboard: true,
                show: false
            });

            $('#importModal').modal({
                backdrop: true,
                keyboard: true,
                show: false
            });
        });
    },

    watch: {
        course_id: {
            immediate: true,
            async handler(newCourseId) {
                if (newCourseId) {
                    await this.loadBatches();
                    await this.loadAvailableInstructors();
                    await this.loadInstructors();
                }
            }
        }
    }
};
</script>

<style scoped>
.course-instructor-management {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.filters {
    padding: 1rem;
    border-radius: 0.5rem;
}

.instructors-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.instructor-card {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: box-shadow 0.15s ease-in-out;
}

.instructor-card:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.instructor-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.instructor-info {
    display: flex;
    gap: 1rem;
    flex: 1;
}

.instructor-avatar {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.instructor-details {
    flex: 1;
}

.instructor-name {
    margin: 0 0 0.5rem 0;
    color: #fff;
    font-weight: 600;
}

.instructor-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.instructor-email,
.instructor-batch {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    font-size: 0.875rem;
}

.instructor-course {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    font-size: 0.875rem;
}

.instructor-email i {
    color: #28a745;
}

.instructor-batch i {
    color: #007bff;
}

.instructor-course i {
    color: #ffc107;
}

.instructor-dates {
    margin-top: 0.5rem;
    font-size: 0.75rem;
}

.instructor-dates i {
    color: #fff;
    margin-right: 0.25rem;
}

.instructor-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .instructor-header {
        flex-direction: column;
        gap: 1rem;
    }

    .instructor-info {
        width: 100%;
    }

    .instructor-actions {
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .instructor-meta {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filters .row {
        margin: 0;
    }

    .filters .col-md-4 {
        padding: 0.25rem;
        margin-bottom: 0.5rem;
    }
}

/* Status indicators */
.instructor-card.status-inactive {
    opacity: 0.7;
}

/* Loading animation */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Enhanced form styling */
.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.modal-dialog {
    margin: 1.75rem auto;
}

/* Filter styling */
.filters .form-control {
    background-color: white;
    border: 1px solid #ced4da;
}

.filters .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #007bff;
}

/* Button hover effects */
.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Card header styling */
.card-header {
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

/* Animation for instructor cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.instructor-card {
    animation: fadeInUp 0.3s ease-out;
}

/* Pagination styling */
.pagination-wrapper {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination-info {
    margin-top: 0.5rem;
}

/* Modal enhancements */
.info-group {
    margin-bottom: 1rem;
}

.info-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
    display: block;
}

.info-group p {
    margin: 0;
    padding: 0.375rem 0;
    color: #6c757d;
}

/* Form enhancements */
.form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Status badges */
.badge-success {
    background-color: #28a745;
}

.badge-secondary {
    background-color: #6c757d;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

/* Button loading states */
.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

/* Responsive enhancements */
@media (max-width: 768px) {
    .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination .page-item {
        margin: 0.125rem;
    }
}

/* Input group styling */
.input-group-text {
    border-color: #ced4da;
}

/* Text colors */
.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #fff !important;
}
</style>
