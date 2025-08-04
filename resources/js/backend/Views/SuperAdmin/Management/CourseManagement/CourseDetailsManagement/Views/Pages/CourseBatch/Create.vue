<template>
    <div class="course-batch-create">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus mr-2"></i>
                    নতুন কোর্স ব্যাচ তৈরি করুন
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    কোর্সের জন্য একটি নতুন ব্যাচ তৈরি করুন। API কল পরে যোগ করা হবে।
                </div>

                <form @submit.prevent="createBatch">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_name" class="form-label">ব্যাচের নাম *</label>
                                <input 
                                    type="text" 
                                    id="batch_name"
                                    v-model="formData.batch_name"
                                    class="form-control"
                                    placeholder="ব্যাচ ১"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch_code" class="form-label">ব্যাচ কোড</label>
                                <input 
                                    type="text" 
                                    id="batch_code"
                                    v-model="formData.batch_code"
                                    class="form-control"
                                    placeholder="BATCH001"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">শুরুর তারিখ</label>
                                <input 
                                    type="date" 
                                    id="start_date"
                                    v-model="formData.start_date"
                                    class="form-control"
                                >
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">শেষের তারিখ</label>
                                <input 
                                    type="date" 
                                    id="end_date"
                                    v-model="formData.end_date"
                                    class="form-control"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_students" class="form-label">সর্বোচ্চ শিক্ষার্থী</label>
                                <input 
                                    type="number" 
                                    id="max_students"
                                    v-model="formData.max_students"
                                    class="form-control"
                                    placeholder="৫০"
                                    min="1"
                                >
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">অবস্থা</label>
                                <select 
                                    id="status"
                                    v-model="formData.status"
                                    class="form-control"
                                >
                                    <option value="active">সক্রিয়</option>
                                    <option value="inactive">নিষ্ক্রিয়</option>
                                    <option value="completed">সম্পন্ন</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">বিবরণ</label>
                        <textarea 
                            id="description"
                            v-model="formData.description"
                            class="form-control"
                            rows="4"
                            placeholder="ব্যাচ সম্পর্কে বিস্তারিত তথ্য..."
                        ></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            :disabled="submitting"
                        >
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            <i v-else class="fas fa-save mr-1"></i>
                            {{ submitting ? 'সংরক্ষণ হচ্ছে...' : 'ব্যাচ তৈরি করুন' }}
                        </button>
                        
                        <router-link 
                            :to="{ name: 'CourseBatchAll', params: { id: $route.params.id } }"
                            class="btn btn-secondary ml-2"
                        >
                            <i class="fas fa-arrow-left mr-1"></i>
                            ফিরে যান
                        </router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseBatchCreate',
    
    data() {
        return {
            submitting: false,
            formData: {
                batch_name: '',
                batch_code: '',
                start_date: '',
                end_date: '',
                max_students: 50,
                status: 'active',
                description: '',
            },
        };
    },
    
    methods: {
        async createBatch() {
            this.submitting = true;
            
            try {
                // TODO: API call will be implemented later
                console.log('Creating batch:', this.formData);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.$toast.success('ব্যাচ সফলভাবে তৈরি হয়েছে!');
                
                // Navigate back to all batches
                this.$router.push({ 
                    name: 'CourseBatchAll', 
                    params: { id: this.$route.params.id } 
                });
                
            } catch (error) {
                this.$toast.error('ব্যাচ তৈরি করতে ত্রুটি হয়েছে!');
                console.error('Error creating batch:', error);
            } finally {
                this.submitting = false;
            }
        },
    },
};
</script>

<style scoped>
.course-batch-create {
    max-width: 100%;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-actions {
        text-align: center;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
}
</style>
