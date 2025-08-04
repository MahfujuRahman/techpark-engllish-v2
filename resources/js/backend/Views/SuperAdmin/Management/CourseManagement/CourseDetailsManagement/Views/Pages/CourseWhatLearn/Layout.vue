<template>
    <div class="course-what-learn-layout">
        <div class="setting_management">
            <!-- Sidebar Navigation -->
            <div class="navs">
                <ul>
                    <li>
                        <router-link 
                            :to="{ name: 'CourseWhatLearnAll', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>সব তালিকা</div>
                        </router-link>
                    </li>
                    
                    <li>
                        <router-link 
                            :to="{ name: 'CourseWhatLearnCreate', params: { id: $route.params.id } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>নতুন যোগ করুন</div>
                        </router-link>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content Area -->
            <div class="setting_body custom_scroll">
                <!-- Content Header -->
                <div class="content-header mb-4">
                    <h4 class="content-title">কোর্সটি করে যা শিখবেন</h4>
                    <p class="content-description">
                        এই সেকশনে কোর্স সম্পন্ন করার পর শিক্ষার্থীরা কী কী শিখতে পারবে তার তালিকা যোগ করুন।
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
    name: 'CourseWhatLearnLayout',
};
</script>

<style scoped>
.course-what-learn-layout {
    min-height: 100vh;
}

.setting_management {
    display: flex;
    min-height: 100vh;
}

.navs {
    min-width: 250px;
    /* background-color: #f8f9fa; */
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
    /* background-color: #e3f2fd; */
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
    padding: 30px;
    /* background-color: #ffffff; */
}

.custom_scroll {
    overflow-y: auto;
    max-height: 100vh;
}

.content-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 15px;
}

.content-title {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 8px;
}

.content-description {
    color: #ffffff;
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .setting_management {
        flex-direction: column;
    }
    
    .navs {
        min-width: auto;
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }
    
    .navs ul {
        display: flex;
        overflow-x: auto;
        padding: 0 10px;
    }
    
    .navs li {
        margin-bottom: 0;
        margin-right: 10px;
        white-space: nowrap;
    }
    
    .nav-item {
        border-left: none;
        border-bottom: 3px solid transparent;
        padding: 10px 15px;
    }
    
    .nav-item:hover,
    .nav-item.router-link-active {
        border-left: none;
        border-bottom-color: #1976d2;
    }
    
    .setting_body {
        padding: 20px 15px;
    }
}
</style>
