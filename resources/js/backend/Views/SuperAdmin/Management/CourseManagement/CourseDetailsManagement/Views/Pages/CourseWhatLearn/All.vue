<template>
    <div class="course-what-learn-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>
                        শিক্ষার্থীরা যা শিখবে - সব তালিকা
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseWhatLearnCreate', params: { id: $route.params.id } }"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            নতুন যোগ করুন
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    কোর্স সম্পন্ন করার পর শিক্ষার্থীরা যা শিখবে তার তালিকা। API কল পরে যোগ করা হবে।
                </div>

                <!-- Learning Items List -->
                <div class="learning-items">
                    <div v-for="item in learningItems" :key="item.id" class="learning-item">
                        <div class="item-content">
                            <div class="item-icon">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div class="item-details">
                                <h6 class="item-title">{{ item.title }}</h6>
                                <p class="item-description">{{ item.description }}</p>
                                <div class="item-meta">
                                    <span class="badge badge-info">{{ item.category }}</span>
                                    <small class="text-muted ml-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ formatDate(item.created_at) }}
                                    </small>
                                </div>
                            </div>
                            <div class="item-actions">
                                <router-link 
                                    :to="{ name: 'CourseWhatLearnEdit', params: { id: $route.params.id, itemId: item.id } }"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <router-link 
                                    :to="{ name: 'CourseWhatLearnDetails', params: { id: $route.params.id, itemId: item.id } }"
                                    class="btn btn-sm btn-outline-info ml-1"
                                >
                                    <i class="fas fa-eye"></i>
                                </router-link>
                                <button 
                                    @click="deleteItem(item.id)"
                                    class="btn btn-sm btn-outline-danger ml-1"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="learningItems.length === 0" class="empty-state text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h5>কোন শিক্ষণীয় বিষয় যোগ করা হয়নি</h5>
                        <p class="text-muted">কোর্সে শিক্ষার্থীরা কী শিখবে তা যোগ করতে নিচের বাটনে ক্লিক করুন।</p>
                        <router-link 
                            :to="{ name: 'CourseWhatLearnCreate', params: { id: $route.params.id } }"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            প্রথম আইটেম যোগ করুন
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseWhatLearnAll',
    
    data() {
        return {
            loading: true,
            learningItems: [],
        };
    },
    
    methods: {
        async fetchLearningItems() {
            try {
                // TODO: API call will be implemented later
                console.log('Fetching learning items for course:', this.$route.params.id);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Simulate data
                this.learningItems = [
                    {
                        id: 1,
                        title: 'ওয়েব ডেভেলপমেন্টের মূল বিষয়',
                        description: 'HTML, CSS, JavaScript এর মাধ্যমে আধুনিক ওয়েবসাইট তৈরি করতে শিখবেন।',
                        category: 'প্রযুক্তিগত দক্ষতা',
                        created_at: '2024-01-01T00:00:00Z',
                    },
                    {
                        id: 2,
                        title: 'রেসপন্সিভ ডিজাইন',
                        description: 'বিভিন্ন ডিভাইসের জন্য উপযুক্ত ওয়েবসাইট ডিজাইন করার কৌশল শিখবেন।',
                        category: 'ডিজাইন',
                        created_at: '2024-01-02T00:00:00Z',
                    },
                ];
                
            } catch (error) {
                console.error('শিক্ষণীয় বিষয় লোড করতে ত্রুটি হয়েছে!', error);
                console.error('Error fetching learning items:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async deleteItem(itemId) {
            if (!confirm('আপনি কি নিশ্চিত যে এই আইটেমটি মুছে ফেলতে চান?')) {
                return;
            }
            
            try {
                // TODO: API call will be implemented later
                console.log('Deleting learning item:', itemId);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // Remove from local array
                this.learningItems = this.learningItems.filter(item => item.id !== itemId);
                
                console.log('আইটেম সফলভাবে মুছে ফেলা হয়েছে!');
                
            } catch (error) {
                console.error('আইটেম মুছে ফেলতে ত্রুটি হয়েছে!', error);
                console.error('Error deleting item:', error);
            }
        },
        
        formatDate(date) {
            if (!date) return 'অজানা';
            return new Date(date).toLocaleDateString('bn-BD');
        },
    },
    
    async mounted() {
        await this.fetchLearningItems();
    },
};
</script>

<style scoped>
.course-what-learn-all {
    max-width: 100%;
}

.learning-items {
    margin-top: 20px;
}

.learning-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.learning-item:hover {
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.item-content {
    display: flex;
    align-items: flex-start;
    padding: 20px;
}

.item-icon {
    margin-right: 15px;
    margin-top: 5px;
}

.item-icon i {
    font-size: 1.2rem;
}

.item-details {
    flex: 1;
}

.item-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 8px;
}

.item-description {
    color: #6c757d;
    margin-bottom: 10px;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    align-items: center;
}

.item-actions {
    display: flex;
    align-items: flex-start;
    gap: 5px;
}

.empty-state {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    margin: 20px 0;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.header-actions .btn {
    border-radius: 20px;
    font-size: 0.9rem;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

/* Responsive Design */
@media (max-width: 768px) {
    .item-content {
        flex-direction: column;
    }
    
    .item-icon {
        margin-right: 0;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .item-actions {
        width: 100%;
        justify-content: center;
        margin-top: 15px;
    }
    
    .header-actions {
        margin-top: 15px;
    }
    
    .header-actions .btn {
        width: 100%;
    }
}
</style>
