<template>
    <div class="course-module-create">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus mr-2"></i>
                    Create Course Module
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Course modules are managed through the comprehensive module builder. Use the main module page to create and manage your course structure.
                </div>
                
                <div class="text-center py-4">
                    <router-link 
                        :to="{ name: 'CourseModuleAll', params: { id: $route.params.id } }" 
                        class="btn btn-primary btn-lg"
                    >
                        <i class="fas fa-arrow-right mr-2"></i>
                        Go to Module Builder
                    </router-link>
                </div>
                
                <div class="feature-list mt-4">
                    <h6 class="mb-3">Module Builder Features:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Create Milestones to organize your course
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Add Modules within each milestone
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Create Classes with video content and schedules
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Manage class routines and resources
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Upload CSV for bulk module creation
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseModuleCreate',
    
    mounted() {
        console.log('CourseModuleCreate component mounted');
    },
};
</script>

<style scoped>
.course-module-create {
    max-width: 100%;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

.feature-list {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.feature-list h6 {
    color: #495057;
    font-weight: 600;
}

.feature-list li {
    color: #6c757d;
}

.feature-list .text-success {
    color: #28a745 !important;
}
</style>