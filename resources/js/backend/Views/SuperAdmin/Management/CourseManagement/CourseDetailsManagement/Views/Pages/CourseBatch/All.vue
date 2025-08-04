<template>
    <div class="course-batch-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">কোর্স ব্যাচ তালিকা</h6>
                    <router-link 
                        :to="{ name: 'CourseBatchCreate', params: { id: $route.params.id } }" 
                        class="btn btn-primary btn-sm"
                    >
                        <i class="fas fa-plus mr-1"></i>
                        নতুন ব্যাচ তৈরি করুন
                    </router-link>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ব্যাচের নাম</th>
                                <th>ভর্তির শেষ তারিখ</th>
                                <th>মূল্য</th>
                                <th>ছাড়ের মূল্য</th>
                                <th>সীট সংখ্যা</th>
                                <th>বুকিং</th>
                                <th>স্ট্যাটাস</th>
                                <th>কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody v-if="batches?.length">
                            <tr v-for="(batch, index) in batches" :key="batch.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ batch.batch_name }}</td>
                                <td>{{ formatDate(batch.admission_end_date) }}</td>
                                <td>{{ formatCurrency(batch.course_price) }}</td>
                                <td>{{ formatCurrency(batch.after_discount_price) }}</td>
                                <td>{{ batch.batch_student_limit }}</td>
                                <td>
                                    <span class="booking-info">
                                        {{ batch.seat_booked || 0 }}/{{ batch.batch_student_limit }}
                                        <small class="text-muted">({{ batch.booked_percent || 0 }}%)</small>
                                    </span>
                                </td>
                                <td>
                                    <span 
                                        :class="['badge', batch.status === 'active' ? 'badge-success' : 'badge-danger']"
                                    >
                                        {{ batch.status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link 
                                            :to="{ name: 'CourseBatchDetails', params: { id: $route.params.id, batch_id: batch.id } }" 
                                            class="btn btn-sm btn-outline-primary"
                                            title="বিস্তারিত"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <router-link 
                                            :to="{ name: 'CourseBatchEdit', params: { id: $route.params.id, batch_id: batch.id } }" 
                                            class="btn btn-sm btn-outline-warning"
                                            title="সম্পাদনা"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button 
                                            @click="deleteBatch(batch)" 
                                            class="btn btn-sm btn-outline-danger"
                                            title="মুছে ফেলুন"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="no-data-container">
                                        <div class="no-data-icon">
                                            <i class="fas fa-calendar-alt fa-3x"></i>
                                        </div>
                                        <div class="no-data-text">
                                            <h6>কোন ব্যাচ পাওয়া যায়নি</h6>
                                            <p>এই কোর্সের জন্য নতুন ব্যাচ তৈরি করুন।</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">লোড হচ্ছে...</span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';

export default {
    name: 'CourseBatchAll',
    
    data() {
        return {
            batches: [],
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse', 'loading']),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['clearMessages']),
        
        async getCourseBatches() {
            const courseId = this.$route.params.id;
            if (!courseId) return;
            
            try {
                const response = await axios.get(`/api/v1/course-batch/all?course_id=${courseId}`);
                this.batches = response.data.data || [];
            } catch (error) {
                this.$toast.error('ব্যাচ তালিকা লোড করতে ত্রুটি হয়েছে');
                console.error('Error fetching course batches:', error);
            }
        },
        
        async deleteBatch(batch) {
            if (await this.$confirm('আপনি কি নিশ্চিত যে আপনি এই ব্যাচটি মুছে ফেলতে চান?')) {
                try {
                    await axios.post('/api/v1/course-batch/soft-delete', { id: batch.id });
                    this.$toast.success('ব্যাচ সফলভাবে মুছে ফেলা হয়েছে!');
                    await this.getCourseBatches();
                } catch (error) {
                    this.$toast.error('ব্যাচ মুছে ফেলতে ত্রুটি হয়েছে!');
                    console.error('Error deleting batch:', error);
                }
            }
        },
        
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('bn-BD', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },
        
        formatCurrency(amount) {
            if (!amount) return '০ ৳';
            return new Intl.NumberFormat('bn-BD', {
                style: 'currency',
                currency: 'BDT',
                minimumFractionDigits: 0
            }).format(amount);
        }
    },
    
    async mounted() {
        await this.getCourseBatches();
    }
};
</script>

<style scoped>
.course-batch-all {
    position: relative;
}

.no-data-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-data-icon {
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-data-text h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.no-data-text p {
    margin: 0;
    font-size: 0.9rem;
}

.booking-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.booking-info small {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
    font-size: 0.9rem;
}

.table td {
    font-size: 0.9rem;
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
    }
}
</style>
