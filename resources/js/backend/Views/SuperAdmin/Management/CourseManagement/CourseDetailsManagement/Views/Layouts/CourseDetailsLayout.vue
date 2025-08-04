<template>
    <div class="course-details-layout">
        <div class="setting_management">
            <!-- Sidebar Navigation -->
            <div class="navs">
                <ul>
                    <li>
                        <router-link :to="{ name: 'AllCourses' }" class="nav-item">
                            <i class="far fa-circle mr-1"></i>
                            <div>সকল কোর্স</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseOverview', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স ওভারভিউ</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseHelp', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>এই কোর্সটি আপনাকে কিভাবে সাহায্য করবে</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseBatch', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স ইনফরমেশন</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseWhatLearn', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্সটি করে যা শিখবেন</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseJobPosition', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্সটি যেভাবে সাজানো হয়েছে</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseForWhom', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্সটি যাদের জন্য</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseWhyLearn', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্সের এক্সক্লুসিভ ফিচার</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseModuleText', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্সে মডিউল</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseBanner', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স ব্যানার</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CoursePreview', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স প্রিভিউ</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseModule', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course class modules</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseClassQuiz', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course class quiz</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseTrainer', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স ট্রেনার</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseRoutines', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course routine</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseFaq', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>সাধারণ জিজ্ঞাসা</div>
                        </router-link>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content Area -->
            <div class="setting_body custom_scroll">
                <!-- Course Header -->
                <div v-if="currentCourse" class="course-header mb-4">
                    <div class="course-info">
                        <div class="course-image">
                            <img 
                                v-if="currentCourse.image && !imageError" 
                                :src="`/storage/uploads/courses/${currentCourse.image}`" 
                                :alt="currentCourse.title"
                                class="img-fluid rounded"
                                @error="handleImageError"
                            >
                            <div v-else class="no-image">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                        <div class="course-details">
                            <h4 class="course-title">{{ currentCourse.title }}</h4>
                            <p class="course-type">{{ currentCourse.type }}</p>
                            <span 
                                :class="['badge', currentCourse.status === 'active' ? 'badge-success' : 'badge-danger']"
                            >
                                {{ currentCourse.status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'AllCourses' }">সকল কোর্স</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ currentCourse.title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Loading State -->
                <div v-if="loading" class="loading-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">লোড হচ্ছে...</span>
                    </div>
                </div>
                
                <!-- Error Message -->
                <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ errorMessage }}
                    <button type="button" class="close" @click="clearMessages" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Success Message -->
                <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ successMessage }}
                    <button type="button" class="close" @click="clearMessages" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <!-- Router View for Course Content -->
                <router-view />
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import { useCourseDetailsStore } from '../../Store/courseDetailsStore.js';

export default {
    name: 'CourseDetailsLayout',
    
    setup() {
        const store = useCourseDetailsStore();
        const imageError = ref(false);
        
        return { 
            store,
            imageError
        };
    },
    
    computed: {
        currentCourse() {
            return this.store.currentCourse;
        },
        loading() {
            return this.store.loading;
        },
        errorMessage() {
            return this.store.errorMessage;
        },
        successMessage() {
            return this.store.successMessage;
        }
    },
    
    async mounted() {
        const courseSlug = this.$route.params.id; // This is actually the slug now
        
        if (courseSlug) {
            try {
                // First check if course is in localStorage
                const storedCourse = localStorage.getItem('current_course');
                if (storedCourse) {
                    const course = JSON.parse(storedCourse);
                    if (course.slug === courseSlug) {
                        this.store.setCurrentCourse(course);
                    }
                }
                
                // Always fetch fresh data using slug
                await this.store.getCourseDetails(courseSlug);
            } catch (error) {
                console.error('Error loading course details:', error);
                // Redirect to all courses if course not found
                if (error.response?.status === 404) {
                    this.$router.push({ name: 'AllCourses' });
                }
            }
        }
    },
    
    watch: {
        '$route.params.id': {
            async handler(newSlug, oldSlug) {
                if (newSlug && newSlug !== oldSlug) {
                    try {
                        await this.store.getCourseDetails(newSlug);
                    } catch (error) {
                        console.error('Error loading course details:', error);
                        if (error.response?.status === 404) {
                            this.$router.push({ name: 'AllCourses' });
                        }
                    }
                }
            },
            immediate: false
        },
        
        currentCourse() {
            // Reset image error when course changes
            this.imageError = false;
        }
    },
    
    methods: {
        clearMessages() {
            this.store.clearMessages();
        },
        
        handleImageError() {
            this.imageError = true;
            console.log('Course image failed to load');
        }
    },
    
    beforeUnmount() {
        // Clear messages when leaving the component
        this.store.clearMessages();
    }
};
</script>

<style scoped>
.course-details-layout {
    height: 100%;
}

.setting_management {
    display: flex;
    height: 100vh;
    /* background-color: #f8f9fa; */
}

.navs {
    width: 300px;
    /* background-color: #fff; */
    border-right: 1px solid #dee2e6;
    overflow-y: auto;
}

.navs ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.navs li {
    border-bottom: 1px solid #f1f1f1;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    color: #fff;
}

.nav-item:hover {
    /* background-color: #f8f9fa; */
    color: #007bff;
    text-decoration: none;
}

.nav-item.router-link-active,
.nav-item.router-link-exact-active {
    background-color: #007bff;
    color: white;
}

.nav-item i {
    margin-right: 8px;
    font-size: 0.9rem;
}

.nav-item div {
    font-size: 0.9rem;
    line-height: 1.3;
}

.setting_body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    /* background-color: #fff; */
}

.custom_scroll {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 transparent;
}

.custom_scroll::-webkit-scrollbar {
    width: 6px;
}

.custom_scroll::-webkit-scrollbar-track {
    background: transparent;
}

.custom_scroll::-webkit-scrollbar-thumb {
    background-color: #c1c1c1;
    border-radius: 3px;
}

.course-header {
    /* background-color: #f8f9fa; */
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.course-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.course-image {
    width: 80px;
    height: 80px;
    margin-right: 20px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e9ecef;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: #6c757d;
}

.course-details {
    flex: 1;
}

.course-title {
    margin: 0 0 5px 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
}

.course-type {
    margin: 0 0 10px 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6c757d;
}

.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
}

.alert {
    margin-bottom: 20px;
}

.badge {
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .setting_management {
        flex-direction: column;
        height: auto;
    }
    
    .navs {
        width: 100%;
        max-height: 200px;
    }
    
    .course-info {
        flex-direction: column;
        text-align: center;
    }
    
    .course-image {
        margin-right: 0;
        margin-bottom: 15px;
    }
}
</style>
