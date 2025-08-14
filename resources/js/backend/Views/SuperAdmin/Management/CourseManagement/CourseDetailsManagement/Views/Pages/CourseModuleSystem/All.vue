<template>
    <div class="course-module-all custom_scroll">
        <form class="course_module_form" @submit.prevent="submit_course_module($event)">
            <!-- Top Action Buttons -->
            <div class="action-buttons mb-4">
                <!-- <router-link :to="{ name: 'CourseModuleCSV', params: { id: $route.params.id } }"
                    class="btn btn-sm btn-primary mb-2 mr-1">
                    <i class="fa-solid fa-upload mr-1"></i> <span>Upload CSV</span>
                </router-link> -->

                <router-link :to="{ name: 'CourseMilestone', params: { id: $route.params.id } }"
                    class="btn btn-sm btn-primary mb-2 mr-1">
                    <i class="fa-solid fa-flag mr-1"></i> <span>Milestones</span>
                </router-link>

                <router-link :to="{ name: 'CourseModulesAll', params: { id: $route.params.id } }"
                    class="btn btn-sm btn-primary mb-2 mr-1">
                    <i class="fa-solid fa-book mr-1"></i> <span>Modules</span>
                </router-link>

                <router-link :to="{ name: 'CourseClassAll', params: { id: $route.params.id } }"
                    class="btn btn-sm btn-primary mb-2 mr-1">
                    <i class="fa-solid fa-chalkboard-teacher mr-1"></i> <span>Classes</span>
                </router-link>
            </div>

            <!-- Milestones -->
            <div v-if="milestones.length === 0" class="empty-state">
                <div class="text-center py-5">
                    <h5 class="text-muted mb-4">No milestones created yet</h5>
                    <button type="button" @click.prevent="append_new_milstone()" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Create Your First Milestone
                    </button>
                </div>
            </div>

            <div v-for="(milestone, index) in milestones" :key="index" class="milestones group">
                <div class="top">
                    <div class="title">
                        <h4 class="heading">
                            <span class="badge badge-primary"> {{ index + 1 }}</span>
                            Milestone
                        </h4>
                        <div class="actions">
                            <button @click.prevent="remove_milestone(milestones, index)" v-if="milestones.length > 1"
                                type="button" class="btn btn-sm btn-outline-danger">Remove</button>
                        </div>
                    </div>
                    <div class="form_group">
                        <label for="">
                            Title
                        </label>
                        <input type="text" class="form-control" v-model="milestone.title">
                    </div>
                    <div class="form_group mb-4">
                        <label for="">
                            Milestone no
                        </label>
                        <input type="text" class="form-control" v-model="milestone.milestone_no">
                    </div>
                </div>

                <!-- Modules within Milestone -->
                <template v-if="isMilestoneComplete(milestone)">
                    <div class="modules group" v-for="(module, moduleIndex) in milestone.modules" :key="moduleIndex">
                    <div class="top">
                        <div class="title">
                            <h4 class="heading">
                                <span class="badge badge-primary"> {{ moduleIndex + 1 }}</span>
                                Module
                            </h4>
                            <div class="actions">
                                <button @click.prevent="remove_module(milestone.modules, moduleIndex)"
                                    v-if="milestone.modules.length > 1" type="button"
                                    class="btn btn-sm btn-outline-danger">Remove</button>
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">
                                Module No
                            </label>
                            <div class="input">
                                <input type="number" class="form-control" v-model="module.module_no">
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">Module title</label>
                            <div class="input">
                                <input type="text" class="form-control" v-model="module.title">
                            </div>
                        </div>
                    </div>

                    <!-- Classes within Module -->
                    <div class="classes group" v-for="(classs, classIndex) in module.classes" :key="classIndex">
                        <div class="top pb-0">
                            <div class="title">
                                <h4 class="heading">
                                    <span class="badge badge-primary"> {{ classIndex + 1 }}</span>
                                    Class
                                </h4>
                                <div class="actions">
                                    <button @click.prevent="remove_class(module.classes, classIndex)"
                                        v-if="module.classes.length > 1" type="button"
                                        class="btn btn-sm btn-outline-danger">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="form_group">
                            <label for="">Class No</label>
                            <div class="input">
                                <input type="number" class="form-control" v-model="classs.class_no">
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">Title</label>
                            <div class="input">
                                <input type="text" class="form-control" v-model="classs.title">
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">Type</label>
                            <div class="input">
                                <select v-model="classs.type" class="form-control">
                                    <option value="recorded">Recorded</option>
                                    <option value="live">Live</option>
                                </select>
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">Class video link</label>
                            <div class="input">
                                <input type="text" class="form-control" v-model="classs.class_video_link">
                            </div>
                        </div>
                        <div class="form_group">
                            <label for="">Class video poster</label>
                            <div class="input">
                                <div class="image-upload-container">
                                    <div v-if="classs.imagePreview" class="current-image">
                                        <img :src="classs.imagePreview" alt="Class Poster" class="img-fluid rounded">
                                        <button type="button" @click.prevent="removeClassPoster(index, moduleIndex, classIndex)" class="btn btn-sm btn-danger remove-image-btn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div v-else class="upload-placeholder" @click.prevent="openClassPosterInput(index, moduleIndex, classIndex)">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <p>Upload Image</p>
                                        <small>JPG, PNG, GIF (Max 2MB)</small>
                                    </div>
                                    <input :ref="`posterInput_${index}_${moduleIndex}_${classIndex}`" type="file" :name="`class_video_poster[${classs.title}]`" accept="image/*" class="d-none" @change="handleClassPosterUpload($event, index, moduleIndex, classIndex)">
                                </div>
                            </div>
                        </div>
                      
                    </div>

                    <!-- Add Class Button -->
                    <div v-if="isModuleComplete(module)" class="add_class classes group">
                        <button type="button" @click.prevent="append_new_class(module)"
                            class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-plus mr-1"></i>
                            Create Class
                        </button>
                    </div>
                </div>

                <!-- Add Module Button -->
                <div v-if="isMilestoneComplete(milestone)" class="add_class modules group">
                    <button type="button" @click.prevent="append_new_module(milestone)"
                        class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-plus mr-1"></i>
                        Create New Module for Milestone - {{ index + 1 }}
                    </button>
                </div>
                </template>
            </div>

            <!-- Add Milestone Button -->
            <div v-if="milestones.length === 0 || milestones.every(m => isMilestoneComplete(m))" class="add_class group">
                <button type="button" @click.prevent="append_new_milstone()" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-plus mr-1"></i>
                    Create New Milestone
                </button>
            </div>

            <hr class="my-4">

            <!-- Submit Button -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save mr-2"></i>
                    Submit Course Modules
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';
export default {
    name: 'CourseModuleAll',

    data() {
        return {
            milestones: []
        };
    },

    computed: {
        // Check if milestone has required fields filled
        isMilestoneComplete() {
            return (milestone) => {
                return milestone.title && milestone.title.trim() !== '' && 
                       milestone.milestone_no && milestone.milestone_no.toString().trim() !== '';
            };
        },
        
        // Check if module has required fields filled
        isModuleComplete() {
            return (module) => {
                return module.title && module.title.trim() !== '' && 
                       module.module_no && module.module_no.toString().trim() !== '';
            };
        }
    },

    async created() {
        await this.get_full_module();
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        async get_full_module() {
            try {
                const courseSlug = this.$route.params.id; // Get slug from route params
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const url = `course/${courseId}/full-module`;
                console.log('Making request to:', axios.defaults.baseURL + url);
                const response = await axios.get(url);
                console.log('Full module response:', response.data);

                if (response.data.status === 'success' && response.data.data) {
                    // The milestones are directly in the course data
                    this.milestones = response.data.data.milestones || [];
                    console.log('Extracted milestones:', this.milestones);
                    
                    // Ensure each milestone has a modules array
                    this.milestones = this.milestones.map(milestone => ({
                        ...milestone,
                        modules: (milestone.modules || []).map(module => ({
                            ...module,
                            classes: (module.classes || []).map(classs => ({
                                ...classs,
                                routine: classs.routine || {
                                    date: "",
                                    time: "",
                                    topic: ""
                                },
                                resource: classs.resource || {
                                    resourse_content: "",
                                    resourse_link: ""
                                },
                                // initialize preview property for UI
                                imagePreview: classs.class_video_poster ? (classs.class_video_poster.startsWith('http') ? classs.class_video_poster : `${window.location.origin}/${classs.class_video_poster}`) : null
                            }))
                        }))
                    }));
                    
                    console.log('Processed milestones:', this.milestones);
                } else {
                    this.milestones = [];
                }

                // Don't auto-create milestone, let user click the button
            } catch (error) {
                console.error('Error fetching full module:', error);
                window.s_error('Failed to load course modules');
                // Create empty state on error
                this.milestones = [];
            }
        },

        async submit_course_module(event) {
            try {
                const courseSlug = this.$route.params.id; // Get slug from route params
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }
                const url = `course/${courseId}/full-module`;
                console.log('Submitting to:', axios.defaults.baseURL + url);
                const response = await axios.post(url, {
                    milestones: this.milestones
                });

                console.log('Submit response:', response.data);
                if (response.data.status === 'success') {
                    window.s_alert(response.data.message || 'Course modules saved successfully!');
                    await this.get_full_module(); // Refresh data
                } else {
                    window.s_error(response.data.message || 'Failed to save course modules');
                }
            } catch (error) {
                console.error('Error submitting course modules:', error);
                window.s_error('Failed to save course modules');
            }
        },

        append_new_class(module) {
            const newClass = {
                course_id: this.$route.params.id,
                id: "",
                class_no: "",
                title: "",
                type: "recorded",
                class_video_link: "",
                class_video_poster: "",
                resource: {
                    resourse_content: "",
                    resourse_link: "",
                },
                routine: {
                    date: "",
                    time: "",
                    topic: "",
                },
                quiz_id: '',
                exam_id: '',
            };

            module.classes.push(newClass);
        },

        handleClassPosterUpload(event, milestoneIndex, moduleIndex, classIndex) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                window.s_alert('Image size must be less than 2MB', 'error');
                return;
            }
            if (!file.type.startsWith('image/')) {
                window.s_alert('Only image files are allowed', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.milestones[milestoneIndex].modules[moduleIndex].classes[classIndex].imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);

            // store the file object on the classs for later submission if needed
            this.milestones[milestoneIndex].modules[moduleIndex].classes[classIndex].__selectedPosterFile = file;
        },

        removeClassPoster(milestoneIndex, moduleIndex, classIndex) {
            const classs = this.milestones[milestoneIndex].modules[moduleIndex].classes[classIndex];
            classs.imagePreview = null;
            classs.__selectedPosterFile = null;
            // also clear server-side reference if exists
            classs.class_video_poster = '';
            const refName = `posterInput_${milestoneIndex}_${moduleIndex}_${classIndex}`;
            if (this.$refs[refName]) {
                // if ref is an array (due to v-for) pick first
                const refEl = Array.isArray(this.$refs[refName]) ? this.$refs[refName][0] : this.$refs[refName];
                if (refEl && refEl.value !== undefined) refEl.value = '';
            }
        },

        openClassPosterInput(milestoneIndex, moduleIndex, classIndex) {
            const refName = `posterInput_${milestoneIndex}_${moduleIndex}_${classIndex}`;
            const refEntry = this.$refs[refName];
            let el = null;
            if (!refEntry) return;
            if (Array.isArray(refEntry)) el = refEntry[0]; else el = refEntry;
            // Some frameworks wrap refs; ensure it's a DOM element with click
            if (el && typeof el.click === 'function') {
                el.click();
            } else if (el && el.$el && typeof el.$el.click === 'function') {
                el.$el.click();
            }
        },

        async remove_class(classes, index) {
            const confirmed = confirm('Are you sure you want to remove this class? Related quizzes will also be deleted.');
            if (confirmed) {
                if (classes[index].slug) {
                    try {
                        await axios.post(`/course-module-classes/destroy/${classes[index].slug}`);
                    } catch (error) {
                        console.error('Error deleting class:', error);
                    }
                }
                classes.splice(index, 1);
            }
        },

        async remove_module(modules, index) {
            const confirmed = confirm('Are you sure you want to remove this module? All classes and quizzes under this module will also be deleted.');
            if (confirmed) {
                if (modules[index].slug) {
                    try {
                        await axios.post(`/course-modules/destroy/${modules[index].slug}`);
                    } catch (error) {
                        console.error('Error deleting module:', error);
                    }
                }
                modules.splice(index, 1);
            }
        },

        async remove_milestone(milestones, index) {
            const confirmed = confirm('Are you sure you want to remove this milestone? All modules and classes and quizzes under this milestone will also be deleted.');
            if (confirmed) {
                if (milestones[index].slug) {
                    try {
                        await axios.post(`/course-milestones/destroy/${milestones[index].slug}`);
                    } catch (error) {
                        console.error('Error deleting milestone:', error);
                    }
                }
                milestones.splice(index, 1);
            }
        },

        append_new_module(milestone) {
            const newModule = {
                id: "",
                module_no: "",
                title: "",
                classes: []
            };

            milestone.modules.push(newModule);
        },

        append_new_milstone() {
            const newMilestone = {
                id: "",
                course_id: this.$route.params.id,
                title: "",
                milestone_no: "",
                modules: []
            };

            this.milestones.push(newMilestone);
        },
    },

    mounted() {
        console.log('CourseModuleAll component mounted');
    },
};
</script>

<style scoped>
.course-module-all {
    max-width: 100%;
    padding: 20px;
}

.action-buttons {
    margin-bottom: 30px;
}

.action-buttons .btn {
    border-radius: 5px;
    font-weight: 500;
}

.course_module_form {
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.group {
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
}

.milestones.group {
    border-left: 4px solid #007bff;
}

.modules.group {
    border-left: 4px solid #28a745;
    margin-left: 20px;
}

.classes.group {
    border-left: 4px solid #ffc107;
    margin-left: 40px;
}

.add_class.group {
    border: 2px dashed #dee2e6;
    text-align: center;
    padding: 15px;
}

.top {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
}

.title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.heading {
    color: #495057;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    font-size: 0.8rem;
    padding: 5px 10px;
}

.actions {
    display: flex;
    gap: 10px;
}

.form_group {
    margin-bottom: 15px;
}

.form_group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.input {
    width: 100%;
}

.btn {
    padding: 6px 12px;
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
    border: 1px solid transparent;
    cursor: pointer;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.875rem;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
    background: transparent;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
    background: transparent;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #212529;
}

.custom_scroll {
    max-height: 100vh;
    overflow-y: auto;
}

/* Badge Colors */
.badge-primary {
    background-color: #007bff;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-module-all {
        padding: 10px;
    }

    .course_module_form {
        padding: 15px;
    }

    .group {
        padding: 15px;
    }

    .modules.group {
        margin-left: 10px;
    }

    .classes.group {
        margin-left: 20px;
    }

    .title {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .actions {
        width: 100%;
        justify-content: flex-end;
    }

    .action-buttons {
        text-align: center;
    }

    .action-buttons .btn {
        margin: 5px;
        display: inline-block;
    }
}

/* Form Styling */
select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 40px;
}

input[type="file"].form-control {
    padding: 6px 12px;
}

input[type="date"].form-control,
input[type="time"].form-control {
    background-color: white;
}

/* Animations */
.group {
    transition: all 0.3s ease;
}

.group:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Loading State */
.loading {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

/* Empty State */
.empty-state {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    margin: 20px 0;
}

.empty-state h5 {
    color: #6c757d;
    font-weight: 500;
}

/* Image upload styles - match CreateCourse.vue */
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