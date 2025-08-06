<template>
    <div class="course-batch-details">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Batch Details
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseBatchEdit', params: { id: $route.params.id, batch_id: $route.params.batch_id } }"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading batch information...</p>
                </div>

                <div v-else-if="batch" class="batch-details">
                    <!-- Basic Information -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-info mr-2"></i>
                            Basic Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Batch Name:</strong>
                                    <span>{{ batch.batch_name }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Student Limit:</strong>
                                    <span>{{ batch.batch_student_limit || 'Not set' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Status:</strong>
                                    <span :class="getStatusClass(batch.status)">
                                        {{ getStatusLabel(batch.status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Seats Booked:</strong>
                                    <span>{{ batch.seat_booked || 0 }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Available Seats:</strong>
                                    <span>{{ (batch.batch_student_limit || 0) - (batch.seat_booked || 0) }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Booking Percentage:</strong>
                                    <span>{{ batch.booked_percent || 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Information -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-calendar mr-2"></i>
                            Date Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Admission Start:</strong>
                                    <span>{{ formatDateTime(batch.admission_start_date) }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Admission End:</strong>
                                    <span>{{ formatDateTime(batch.admission_end_date) }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>First Class Date:</strong>
                                    <span>{{ formatDateTime(batch.first_class_date) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <strong>Class Days:</strong>
                                    <span>{{ batch.class_days || 'Not set' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Class Time:</strong>
                                    <span v-if="batch.class_start_time && batch.class_end_time">
                                        {{ formatTime(batch.class_start_time) }} - {{ formatTime(batch.class_end_time) }}
                                    </span>
                                    <span v-else>Not set</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Show Percentage:</strong>
                                    <span>{{ batch.show_percentage_on_cards === 'yes' ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Information -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Price Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <strong>Course Price:</strong>
                                    <span>{{ formatCurrency(batch.course_price) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <strong>Discount:</strong>
                                    <span>{{ batch.course_discount || 0 }}%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-item">
                                    <strong>Final Price:</strong>
                                    <span class="text-success font-weight-bold">{{ formatCurrency(batch.after_discount_price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Statistics -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Statistics
                        </h6>
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="stat-card">
                                    <div class="stat-icon text-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h4>{{ batch.seat_booked || 0 }}</h4>
                                        <p>Enrolled Students</p>
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
                                        <p>Completed</p>
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
                                        <p>Active Students</p>
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
                                        <p>Dropped Students</p>
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
                                Edit Batch
                            </router-link>
                            
                            <button 
                                @click="deleteBatch"
                                class="btn btn-danger ml-2"
                                :disabled="submitting"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else class="fas fa-trash mr-1"></i>
                                Delete Batch
                            </button>
                            
                            <router-link 
                                :to="{ name: 'CourseBatchAll', params: { id: $route.params.id } }"
                                class="btn btn-secondary ml-2"
                            >
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back to List
                            </router-link>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h4>Batch information not found</h4>
                    <p class="text-muted">Please try again</p>
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
                const batchSlug = this.$route.params.batch_id; // Now this contains the slug
                console.log('Fetching batch details for slug:', batchSlug);
                
                // According to your routes: Route::get('{slug}', [Controller::class,'show']);
                const response = await axios.get(`course-batches/${batchSlug}`);
                
                console.log('API Response:', response.data);
                
                // Check if response has the expected structure
                if (response.data && response.data.status === 'success') {
                    this.batch = response.data.data;
                    console.log('Loaded batch:', this.batch);
                } else {
                    console.error('Unexpected response structure:', response.data);
                    this.$toast.error('Failed to load batch information!');
                }
                
            } catch (error) {
                this.$toast.error('Failed to load batch information!');
                console.error('Error fetching batch:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async deleteBatch() {
            if (!confirm('Are you sure you want to delete this batch?')) {
                return;
            }
            
            this.submitting = true;
            
            try {
                const batchSlug = this.batch.slug || this.$route.params.batch_id; // Use slug from data or route
                console.log('Deleting batch with slug:', batchSlug);
                
                await axios.post(`course-batches/destroy/${batchSlug}`);
                
                this.$toast.success('Batch deleted successfully!');
                
                // Navigate back to all batches
                this.$router.push({ 
                    name: 'CourseBatchAll', 
                    params: { id: this.$route.params.id } 
                });
                
            } catch (error) {
                this.$toast.error('Failed to delete batch!');
                console.error('Error deleting batch:', error);
            } finally {
                this.submitting = false;
            }
        },
        
        getStatusLabel(status) {
            const labels = {
                'active': 'Active',
                'inactive': 'Inactive',
                'completed': 'Completed'
            };
            return labels[status] || 'Unknown';
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
            if (!date) return 'Not set';
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },
        
        formatDateTime(date) {
            if (!date) return 'Not set';
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        formatTime(time) {
            if (!time) return 'Not set';
            // Assuming time is in HH:MM format
            const [hours, minutes] = time.split(':');
            const date = new Date();
            date.setHours(parseInt(hours), parseInt(minutes));
            
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        },
        
        formatCurrency(amount) {
            if (!amount) return '$0';
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0
            }).format(amount);
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
    color: #fff;
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
