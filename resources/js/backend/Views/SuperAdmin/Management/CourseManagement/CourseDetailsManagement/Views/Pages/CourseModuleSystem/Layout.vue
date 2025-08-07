<template>
    <div class="course-module-layout">
        <div class="setting_management">
            <!-- Sidebar Navigation -->
            <!-- <div class="navs">
                <ul>
                    <li>
                        <router-link 
                            :to="{ name: 'CourseModuleAll', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Modules</div>
                        </router-link>
                    </li>
                    
                    <li>
                        <router-link 
                            :to="{ name: 'CourseModuleCreate', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Add Module</div>
                        </router-link>
                    </li>

                    <li>
                        <router-link 
                            :to="{ name: 'CourseModuleCSV', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>CSV Upload</div>
                        </router-link>
                    </li>

                    <li>
                        <router-link 
                            :to="{ name: 'CourseAtGlance', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>At Glance</div>
                        </router-link>
                    </li>

                    <li>
                        <router-link 
                            :to="{ name: 'CourseClassQuiz', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Class Quiz</div>
                        </router-link>
                    </li>
                </ul>
            </div> -->
            
            <!-- Main Content Area -->
            <div class="setting_body custom_scroll">
                <!-- Content Header -->
                <div class="content-header mb-4">
                    <h4 class="content-title">Course Modules & Classes</h4>
                    <p class="content-description">
                        Manage course structure with milestones, modules, and classes. Create comprehensive learning paths for your students.
                    </p>
                </div>
                
                <!-- Router View -->
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseModuleLayout',
};
</script>

<style scoped>
.course-module-layout {
    min-height: 100vh;
}

.setting_management {
    display: flex;
    min-height: 100vh;
}

.navs {
    min-width: 130px;
    border-right: 1px solid #dee2e6;
    padding: 20px 0;
}

.navs ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.navs li {
    margin-bottom: 5px;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-item:hover,
.nav-item.router-link-active {
    color: #1976d2;
    text-decoration: none;
    border-left-color: #1976d2;
}

.nav-item i {
    margin-right: 8px;
    font-size: 0.9rem;
}

.setting_body {
    flex: 1;
    padding: 20px;
}

.content-header {
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.content-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 8px;
}

.content-description {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.95rem;
}

.custom_scroll {
    overflow-y: auto;
    max-height: calc(100vh - 40px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .setting_management {
        flex-direction: column;
    }
    
    .navs {
        min-width: 100%;
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding: 10px 0;
    }
    
    .navs ul {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    
    .navs li {
        margin-bottom: 0;
    }
    
    .nav-item {
        padding: 8px 12px;
        border-radius: 5px;
        border-left: none;
        background-color: #e9ecef;
        color: #495057;
        font-size: 0.9rem;
    }
    
    .nav-item:hover,
    .nav-item.router-link-active {
        background-color: #1976d2;
        color: white;
    }
    
    .setting_body {
        padding: 15px;
    }
    
    .content-header {
        padding: 15px;
        text-align: center;
    }
    
    .content-title {
        font-size: 1.1rem;
    }
    
    .content-description {
        font-size: 0.9rem;
    }
}
</style>