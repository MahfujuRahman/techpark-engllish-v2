<template>
    <div class="course-trainer-layout">
        <div class="card">
            <div class="card-body p-0">
                <CourseInstructor />
            </div>
        </div>
    </div>
</template>

<script>
import CourseInstructor from './CourseInstructor.vue';

export default {
    name: 'CourseTrainerLayout',
    
    components: {
        CourseInstructor,
    },
    
    data() {
        return {
            loading: false,
        };
    },
    
    mounted() {
        console.log('CourseTrainerLayout component mounted');
    },
};
</script>

<style scoped>
.course-trainer-layout {
    max-width: 100%;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.card-body {
    padding: 0;
}
</style>