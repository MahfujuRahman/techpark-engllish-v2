<template>
    <div class="course-details-management">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header py-2">
                        <h4 class="card-title">সকল কোর্স তালিকা 🚀</h4>
                        <router-link 
                            :to="{ name: 'CreateCourse' }" 
                            class="btn btn-primary mb-2 float-right"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            <span>নতুন কোর্স তৈরি করুন</span>
                        </router-link>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table_responsive card_body_fixed_height">
                            <table class="table table-hover text-center table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>চিত্র</th>
                                        <th>শিরোনাম</th>
                                        <th>ধরন</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>কার্যক্রম</th>
                                    </tr>
                                </thead>
                                <tbody v-if="store.courses?.data?.length">
                                    <tr v-for="(course, index) in store.courses.data" :key="course.id">
                                        <td>{{ ((store.courses.current_page - 1) * store.courses.per_page) + index + 1 }}</td>
                                        <td>
                                            <img 
                                                v-if="course.image" 
                                                :src="`/${course.image}`" 
                                                alt="Course Image" 
                                                style="height: 60px; width: 60px; object-fit: cover; border-radius: 8px;"
                                            >
                                            <div v-else class="no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        </td>
                                        <td>{{ course.title || 'N/A' }}</td>
                                        <td>{{ course.type || 'N/A' }}</td>
                                        <td>
                                            <span 
                                                :class="['badge', course.status === 'active' ? 'badge-success' : 'badge-danger']"
                                            >
                                                {{ course.status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <router-link 
                                                    :to="{ name: 'CourseDetails', params: { id: course.slug } }" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="বিস্তারিত"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </router-link>
                                                <button 
                                                    @click="editCourse(course)" 
                                                    class="btn btn-sm btn-outline-warning ml-2"
                                                    title="সম্পাদনা"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button 
                                                    @click="deleteCourse(course)" 
                                                    class="btn btn-sm btn-outline-danger ml-2"
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
                                        <td colspan="6">
                                            <div class="no-data-container">
                                                <div class="no-data-icon">
                                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                                </div>
                                                <div class="no-data-text">
                                                    <h5>কোন কোর্স পাওয়া যায়নি</h5>
                                                    <p>নতুন কোর্স যোগ করার জন্য উপরের "নতুন কোর্স তৈরি করুন" বোতামে ক্লিক করুন।</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mx-3" v-if="typeof store.courses == 'object' && store.courses.last_page > 1">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: store.courses.current_page === 1 }">
                                    <button 
                                        class="page-link" 
                                        @click="setPage(store.courses.current_page - 1)"
                                        :disabled="store.courses.current_page === 1"
                                    >
                                        পূর্ববর্তী
                                    </button>
                                </li>
                                
                                <li 
                                    class="page-item" 
                                    v-for="page in paginationRange" 
                                    :key="page"
                                    :class="{ active: page === store.courses.current_page }"
                                >
                                    <button class="page-link" @click="setPage(page)">{{ page }}</button>
                                </li>
                                
                                <li class="page-item" :class="{ disabled: store.courses.current_page === store.courses.last_page }">
                                    <button 
                                        class="page-link" 
                                        @click="setPage(store.courses.current_page + 1)"
                                        :disabled="store.courses.current_page === store.courses.last_page"
                                    >
                                        পরবর্তী
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="card-footer py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="per-page-selector">
                                <label>প্রতি পৃষ্ঠায়:</label>
                                <select v-model="store.paginate" @change="setPaginate" class="form-control form-control-sm d-inline-block w-auto ml-1">
                                    <option value="10">১০</option>
                                    <option value="25">২৫</option>
                                    <option value="50">৫০</option>
                                    <option value="100">১০০</option>
                                </select>
                            </div>
                            <div class="pagination-info">
                                <span v-if="store.courses?.data?.length">
                                    {{ store.courses.from }} - {{ store.courses.to }} of {{ store.courses.total }} টি ফলাফল
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="store.loading" class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">লোড হচ্ছে...</span>
            </div>
        </div>
    </div>
</template>

<script>
import { useCourseDetailsStore } from '../Store/courseDetailsStore.js';

export default {
    name: 'AllCourse',
    
    setup() {
        const store = useCourseDetailsStore();
        return { store };
    },
    
    computed: {
        paginationRange() {
            if (!this.store.courses || !this.store.courses.last_page) return [];
            
            const current = this.store.courses.current_page;
            const last = this.store.courses.last_page;
            const range = 3; // Show 3 pages on each side
            
            let start = Math.max(1, current - range);
            let end = Math.min(last, current + range);
            
            // Adjust if we're near the beginning or end
            if (end - start < range * 2) {
                if (start === 1) {
                    end = Math.min(last, start + range * 2);
                } else {
                    start = Math.max(1, end - range * 2);
                }
            }
            
            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        }
    },
    
    methods: {
        async editCourse(course) {
            // Navigate to edit route with course slug
            this.$router.push({ name: 'EditCourse', params: { id: course.slug } });
        },
        
        async deleteCourse(course) {
            if (await this.confirmAction('আপনি কি নিশ্চিত যে আপনি এই কোর্সটি মুছে ফেলতে চান?')) {
                try {
                    await this.store.deleteCourse(course.slug);
                    this.showSuccessMessage('কোর্স সফলভাবে মুছে ফেলা হয়েছে!');
                    await this.store.getCourses();
                } catch (error) {
                    this.showErrorMessage('কোর্স মুছে ফেলতে ত্রুটি হয়েছে!');
                    console.error('Error deleting course:', error);
                }
            }
        },

        async setPage(page) {
            if (page >= 1 && page <= this.store.courses.last_page) {
                this.store.setPage(page);
                await this.store.getCourses();
            }
        },

        async setPaginate() {
            this.store.setPaginate(this.store.paginate);
            await this.store.getCourses();
        },

        // Utility Methods
        async confirmAction(message) {
            return new Promise((resolve) => {
                if (window.confirm) {
                    resolve(window.confirm(message));
                } else {
                    resolve(confirm(message));
                }
            });
        },

        showSuccessMessage(message) {
            if (window.toaster) {
                window.toaster(message, 'success');
            } else {
                console.log('Success:', message);
            }
        },

        showErrorMessage(message) {
            if (window.toaster) {
                window.toaster(message, 'error');
            } else {
                console.error('Error:', message);
            }
        }
    },
    
    async mounted() {
        await this.store.getCourses();
    }
};
</script>

<style scoped>
.course-details-management {
    position: relative;
}
.card-header{
    display: flex;
    justify-content: space-between;
}

.no-data-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 60px 20px;
    color: #6c757d;
}

.no-data-icon {
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-data-text h5 {
    margin-bottom: 10px;
    font-weight: 600;
}

.no-data-text p {
    margin: 0;
    font-size: 0.9rem;
}

.no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background-color: #f8f9fa;
    border-radius: 8px;
    color: #6c757d;
    margin: 0 auto;
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

.per-page-selector {
    display: flex;
    align-items: center;
}

.per-page-selector label {
    margin: 0;
    font-size: 0.9rem;
}

.pagination-info {
    font-size: 0.9rem;
    color: #6c757d;
}

.card_body_fixed_height {
    min-height: 400px;
}

.table th {
    /* background-color: #f8f9fa; */
    font-weight: 600;
    border-top: none;
}

.badge {
    font-size: 0.8rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
