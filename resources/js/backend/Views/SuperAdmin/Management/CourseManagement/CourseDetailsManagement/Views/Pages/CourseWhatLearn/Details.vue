<template>
    <div class="oursewhatlearn-details">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    শিক্ষণীয় বিষয়ের বিস্তারিত
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    শিক্ষণীয় বিষয়ের বিস্তারিত - API কল পরে যোগ করা হবে। এটি একটি প্রাথমিক টেমপ্লেট।
                </div>
                
                
                <!-- Content will be implemented here -->
                <div class="placeholder-content">
                    <p>এই পেইজটি এখনো সম্পূর্ণ হয়নি। শীঘ্রই আপডেট করা হবে।</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseWhatLearnDetails',
    
    data() {
        return {
            loading: false,
            // Add component specific data here
        };
    },
    
    methods: {
        // Add component specific methods here
    },
    
    mounted() {
        console.log('CourseWhatLearnDetails component mounted');
    },
};
</script>

<style scoped>
.oursewhatlearn-details {
    max-width: 100%;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    /* background-color: #d1ecf1; */
    border-color: #bee5eb;
}

.placeholder-content {
    text-align: center;
    padding: 40px 20px;
    /* background-color: #f8f9fa; */
    border-radius: 8px;
    margin-top: 20px;
}
</style>