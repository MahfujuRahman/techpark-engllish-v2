<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Comments for Seminar: {{ seminar?.title || 'N/A' }}</h4>
                        <button @click="goBack" class="btn btn-secondary float-end">Back to Reviews</button>
                    </div>
                    <div class="card-body">
                        <div v-if="loading" class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div v-else>
                            <div v-for="review in comments" :key="review.id" class="comment-item mb-4 border p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width:44px;height:44px">{{ review.name ?
                                            review.name.charAt(0).toUpperCase() : 'A' }}</div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>{{ review.name || 'Anonymous' }}</strong>
                                            <span class="badge ml-2"
                                                :class="{ 'bg-success': review.is_admin, 'bg-secondary': !review.is_admin }">{{
                                                review.is_admin ? 'Admin' : 'User' }}</span>
                                            <small class="text-muted"> · {{ formatDate(review.created_at) }}</small>
                                        </div>
                                        <div class="mt-1">{{ review.comment }}</div>
                                        <div class="mt-2">
                                            <button @click="toggleReplyForm(review.id)"
                                                class="btn btn-sm btn-link">Reply</button>
                                        </div>
                                        <div v-if="replyForms[review.id]" class="mt-2">
                                            <form @submit.prevent="submitReply(review.id)">
                                                <div class="mb-2">
                                                    <textarea v-model="replyTexts[review.id]" class="form-control"
                                                        rows="3" placeholder="Write a reply..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">Submit
                                                    Reply</button>
                                                <button type="button" @click="toggleReplyForm(review.id)"
                                                    class="btn btn-sm btn-link">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <ReplyThread :replies="review.replies" :level="1" @toggle-reply="toggleReplyForm"
                                    @submit-reply="submitReply" :reply-forms="replyForms" :reply-texts="replyTexts" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
    name: 'Comments',
    components: {
        ReplyThread: {
            name: 'ReplyThread',
            props: {
                replies: {
                    type: Array,
                    default: () => []
                },
                level: {
                    type: Number,
                    default: 1
                },
                replyForms: {
                    type: Object,
                    default: () => ({})
                },
                replyTexts: {
                    type: Object,
                    default: () => ({})
                }
            },
            methods: {
                formatDate(dateString) {
                    if (!dateString) return '';
                    return new Date(dateString).toLocaleDateString();
                },
            },
            template: `
                <div v-if="replies && replies.length" class="replies mt-3 ml-4">
                    <div v-for="reply in replies" :key="reply.id" class="reply-item mb-2 border-start ps-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     :style="{ width: (40 - level * 4) + 'px', height: (40 - level * 4) + 'px' }">
                                    {{ reply.name ? reply.name.charAt(0).toUpperCase() : 'A' }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex align-items-center gap-2">
                                    <strong>{{ reply.name || 'Anonymous' }}</strong>
                                    <span class="badge" :class="{'bg-success': reply.is_admin, 'bg-secondary': !reply.is_admin}">
                                        {{ reply.is_admin ? 'Admin' : 'User' }}
                                    </span>
                                    <small class="text-muted"> · {{ formatDate(reply.created_at) }}</small>
                                </div>
                                <div class="mt-1">{{ reply.comment }}</div>
                                <div class="mt-2" v-if="level < 2">
                                    <button @click="$emit('toggle-reply', reply.id)" class="btn btn-sm btn-link">Reply</button>
                                </div>
                                <div v-if="level < 2 && replyForms[reply.id]" class="mt-2">
                                    <form @submit.prevent="$emit('submit-reply', reply.id)">
                                        <div class="mb-2">
                                            <textarea v-model="replyTexts[reply.id]" class="form-control" rows="3" placeholder="Write a reply..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Submit Reply</button>
                                        <button type="button" @click="$emit('toggle-reply', reply.id)" class="btn btn-sm btn-link">Cancel</button>
                                    </form>
                                </div>
                                <ReplyThread v-if="level < 2"
                                    :replies="reply.replies" 
                                    :level="level + 1"
                                    @toggle-reply="$emit('toggle-reply', $event)"
                                    @submit-reply="$emit('submit-reply', $event)"
                                    :reply-forms="replyForms"
                                    :reply-texts="replyTexts"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            `
        }
    },
    data() {
        return {
            seminar: null,
            comments: [],
            loading: false,
            replyForms: {},
            replyTexts: {},
        };
    },
    mounted() {
        console.log('Comments component mounted');
        console.log('Route params:', this.$route.params);
        this.loadComments();
    },
    methods: {
        async loadComments() {
            this.loading = true;
            try {
                const seminarId = this.$route.params.id;
                console.log('Loading comments for seminar ID:', seminarId);
                const apiUrl = `/seminer-reviews/seminar-details/${seminarId}`;
                console.log('API URL:', apiUrl);

                const response = await axios.get(apiUrl);
                console.log('API Response:', response.data);

                this.seminar = response.data.seminar;
                this.comments = response.data.seminar?.reviews || [];
                console.log('Seminar:', this.seminar);
                console.log('Comments:', this.comments);
                console.log('First comment replies:', this.comments[0]?.replies);
                console.log('Nested replies:', this.comments[0]?.replies?.[0]?.replies);
            } catch (error) {
                console.error('Error loading comments:', error);
                this.$swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load comments'
                });
            } finally {
                this.loading = false;
            }
        },
        toggleReplyForm(reviewId) {
            console.log('Toggling reply form for review ID:', reviewId);
            console.log('Current replyForms state:', this.replyForms);
            this.replyForms[reviewId] = !this.replyForms[reviewId];
            console.log('New replyForms state:', this.replyForms);
            if (!this.replyForms[reviewId]) {
                delete this.replyTexts[reviewId];
            }
        },
        async submitReply(reviewId) {
            try {
                const seminarId = this.$route.params.id;

                // Create form data for the frontend endpoint
                const formData = new FormData();
                formData.append('parent_id', reviewId);
                formData.append('comment', this.replyTexts[reviewId]);
                formData.append('seminar_id', seminarId);

                // Add CSRF token for Laravel
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    formData.append('_token', csrfToken.getAttribute('content'));
                }

                const response = await axios.post(`/seminer-reviews/reply/${seminarId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                // The frontend controller returns redirects, so we need to check the response
                if (response.status === 200 || response.status === 302) {
                    // Reload comments to show the new reply
                    await this.loadComments();

                    // Reset form
                    this.toggleReplyForm(reviewId);

                    this.$swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Reply submitted successfully'
                    });
                }
            } catch (error) {
                console.error('Error submitting reply:', error);

                // Check if it's a validation error
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMessage = 'Validation error:';
                    for (const field in errors) {
                        errorMessage += `\n${field}: ${errors[field].join(', ')}`;
                    }
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessage
                    });
                } else {
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Failed to submit reply'
                    });
                }
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        },
        goBack() {
            this.$router.push('/seminer-reviews/all');
        },
    }
};
</script>
