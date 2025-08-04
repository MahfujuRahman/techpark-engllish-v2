<template>
    <div class="course-batch-layout">
        <div class="course-batch-header">
            <h5 class="section-title">
                <i class="fas fa-calendar-alt mr-2"></i>
                Batch Management
            </h5>
        </div>
        
        <router-view />
    </div>
</template>

<script>
export default {
    name: 'CourseBatchLayout'
};
</script>

<style scoped>
.course-batch-layout {
    width: 100%;
}

.course-batch-header {
    margin-bottom: 20px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin: 0;
}
</style>
