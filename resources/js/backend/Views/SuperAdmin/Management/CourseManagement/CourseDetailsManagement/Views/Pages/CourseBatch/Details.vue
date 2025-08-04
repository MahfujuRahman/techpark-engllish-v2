<template>
    <div class="course-batch-details">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        ব্যাচের বিস্তারিত তথ্য
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseBatchEdit', params: { id: $route.params.id, batch_id: $route.params.batch_id } }"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-edit mr-1"></i>
                            সম্পাদনা
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">লোড হচ্ছে...</span>
                    </div>
                    <p class="mt-2 text-muted">ব্যাচের তথ্য লোড হচ্ছে...</p>
                </div>

                <div v-else-if="batch" class="batch-details">
                    <!-- Basic Information -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-info mr-2"></i>
                            মূল তথ্য
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>ব্যাচের নাম:</strong>
                                    <span>{{ batch.batch_name }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>ব্যাচ কোড:</strong>
                                    <span>{{ batch.batch_code || 'নির্ধারিত নয়' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>অবস্থা:</strong>
                                    <span :class="getStatusClass(batch.status)">
                                        {{ getStatusLabel(batch.status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>সর্বোচ্চ শিক্ষার্থী:</strong>
                                    <span>{{ batch.max_students || 'নির্ধারিত নয়' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>বর্তমান শিক্ষার্থী:</strong>
                                    <span>{{ batch.enrolled_students || 0 }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>খালি আসন:</strong>
                                    <span>{{ (batch.max_students || 0) - (batch.enrolled_students || 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Information -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-calendar mr-2"></i>
                            তারিখের তথ্য
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>শুরুর তারিখ:</strong>
                                    <span>{{ formatDate(batch.start_date) }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>শেষের তারিখ:</strong>
                                    <span>{{ formatDate(batch.end_date) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>তৈরি হয়েছে:</strong>
                                    <span>{{ formatDateTime(batch.created_at) }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>সর্বশেষ আপডেট:</strong>
                                    <span>{{ formatDateTime(batch.updated_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div v-if="batch.description" class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-align-left mr-2"></i>
                            বিবরণ
                        </h6>
                        <div class="description-content">
                            <p>{{ batch.description }}</p>
                        </div>
                    </div>

                    <!-- Progress Statistics -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            পরিসংখ্যান
                        </h6>
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="stat-icon text-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4>{{ batch.enrolled_students || 0 }}</h4>
                                        <p>নিবন্ধিত শিক্ষার্থী</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="stat-icon text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4>{{ batch.completed_students || 0 }}</h4>
                                        <p>সম্পন্নকারী</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="stat-icon text-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4>{{ batch.active_students || 0 }}</h4>
                                        <p>চলমান শিক্ষার্থী</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="stat-icon text-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4>{{ batch.dropped_students || 0 }}</h4>
                                        <p>বাদ পড়া শিক্ষার্থী</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="details-section">
                        <div class="action-buttons">
                            <router-link 
                                :to="{ name: 'CourseBatchEdit', params: { id: $route.params.id, batch_id: $route.params.batch_id } }"
                                class="btn btn-primary"
                            >
                                <i class="fas fa-edit mr-1"></i>
                                ব্যাচ সম্পাদনা করুন
                            </router-link>
                            
                            <button 
                                @click="deleteBatch"
                                class="btn btn-danger ml-2"
                                :disabled="submitting"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else class="fas fa-trash mr-1"></i>
                                ব্যাচ মুছে ফেলুন
                            </button>
                            
                            <router-link 
                                :to="{ name: 'CourseBatchAll', params: { id: $route.params.id } }"
                                class="btn btn-secondary ml-2"
                            >
                                <i class="fas fa-arrow-left mr-1"></i>
                                ফিরে যান
                            </router-link>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h4>ব্যাচ তথ্য পাওয়া যায়নি</h4>
                    <p class="text-muted">দয়া করে পুনরায় চেষ্টা করুন</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseBatchDetails',
    
    data() {
        return {
            loading: true,
            submitting: false,
            batch: null,
        };
    },
    
    methods: {
        async fetchBatch() {
            try {
                // TODO: API call will be implemented later
                console.log('Fetching batch details:', this.$route.params.batch_id);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Simulate loaded data
                this.batch = {
                    id: this.$route.params.batch_id,
                    batch_name: 'ব্যাচ ১',
                    batch_code: 'BATCH001',
                    start_date: '2024-01-01',
                    end_date: '2024-06-30',
                    max_students: 50,
                    enrolled_students: 35,
                    active_students: 30,
                    completed_students: 5,
                    dropped_students: 2,
                    status: 'active',
                    description: 'এটি একটি নমুনা ব্যাচ যা প্রদর্শনের জন্য তৈরি করা হয়েছে।',
                    created_at: '2024-01-01T00:00:00Z',
                    updated_at: '2024-01-15T10:30:00Z',
                };
                
            } catch (error) {
                this.$toast.error('ব্যাচ তথ্য লোড করতে ত্রুটি হয়েছে!');
                console.error('Error fetching batch:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async deleteBatch() {
            if (!confirm('আপনি কি নিশ্চিত যে এই ব্যাচটি মুছে ফেলতে চান?')) {
                return;
            }
            
            this.submitting = true;
            
            try {
                // TODO: API call will be implemented later
                console.log('Deleting batch:', this.batch.id);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.$toast.success('ব্যাচ সফলভাবে মুছে ফেলা হয়েছে!');
                
                // Navigate back to all batches
                this.$router.push({ 
                    name: 'CourseBatchAll', 
                    params: { id: this.$route.params.id } 
                });
                
            } catch (error) {
                this.$toast.error('ব্যাচ মুছে ফেলতে ত্রুটি হয়েছে!');
                console.error('Error deleting batch:', error);
            } finally {
                this.submitting = false;
            }
        },
        
        getStatusLabel(status) {
            const labels = {
                'active': 'সক্রিয়',
                'inactive': 'নিষ্ক্রিয়',
                'completed': 'সম্পন্ন'
            };
            return labels[status] || 'অজানা';
        },
        
        getStatusClass(status) {
            const classes = {
                'active': 'badge badge-success',
                'inactive': 'badge badge-secondary',
                'completed': 'badge badge-primary'
            };
            return classes[status] || 'badge badge-light';
        },
        
        formatDate(date) {
            if (!date) return 'নির্ধারিত নয়';
            return new Date(date).toLocaleDateString('bn-BD');
        },
        
        formatDateTime(date) {
            if (!date) return 'নির্ধারিত নয়';
            return new Date(date).toLocaleString('bn-BD');
        },
    },
    
    async mounted() {
        await this.fetchBatch();
    },
};
</script>

<style scoped>
.course-batch-details {
    max-width: 100%;
}

.details-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.details-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.detail-item {
    margin-bottom: 10px;
    padding: 8px 0;
}

.detail-item strong {
    color: #495057;
    margin-right: 10px;
    display: inline-block;
    min-width: 150px;
}

.description-content {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    border-radius: 5px;
}

.stat-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 15px;
}

.stat-info h4 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #495057;
}

.stat-info p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.action-buttons {
    text-align: center;
    margin-top: 20px;
}

.header-actions .btn {
    border-radius: 20px;
    font-size: 0.9rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .detail-item strong {
        min-width: auto;
        display: block;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .header-actions {
        margin-top: 15px;
    }
    
    .stat-info h4 {
        font-size: 1.5rem;
    }
}
</style>
