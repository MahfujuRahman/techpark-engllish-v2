<template>
    <div class="course-management-layout">
        <router-view />
    </div>
</template>

<script>
export default {
    name: 'CourseManagementLayout'
}
</script>

<style scoped>
.course-management-layout {
    min-height: 100vh;
}
</style>
