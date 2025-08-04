// Course Details Management Routes
import AllCourse from '../Views/AllCourse.vue';
import CreateCourse from '../Views/CreateCourse.vue';
import CourseDetailsLayout from '../Views/Layouts/CourseDetailsLayout.vue';
import CourseManagementLayout from '../Views/Layouts/CourseManagementLayout.vue';

// Course Details Pages
import CourseOverview from '../Views/Pages/CourseOverview.vue';
import CourseBanner from '../Views/Pages/CourseBanner.vue';
import CourseModuleText from '../Views/Pages/CourseModuleText.vue';
import CoursePreview from '../Views/Pages/CoursePreview.vue';

// Course Batch
import CourseBatchLayout from '../Views/Pages/CourseBatch/Layout.vue';
import CourseBatchAll from '../Views/Pages/CourseBatch/All.vue';
import CourseBatchCreate from '../Views/Pages/CourseBatch/Create.vue';
import CourseBatchEdit from '../Views/Pages/CourseBatch/Edit.vue';
import CourseBatchDetails from '../Views/Pages/CourseBatch/Details.vue';

// Course What Will Learn
import CourseWhatLearnLayout from '../Views/Pages/CourseWhatLearn/Layout.vue';
import CourseWhatLearnAll from '../Views/Pages/CourseWhatLearn/All.vue';
import CourseWhatLearnCreate from '../Views/Pages/CourseWhatLearn/Create.vue';
import CourseWhatLearnEdit from '../Views/Pages/CourseWhatLearn/Edit.vue';
import CourseWhatLearnDetails from '../Views/Pages/CourseWhatLearn/Details.vue';

// Course Job Position
import CourseJobPositionLayout from '../Views/Pages/CourseJobPosition/Layout.vue';
import CourseJobPositionAll from '../Views/Pages/CourseJobPosition/All.vue';
import CourseJobPositionCreate from '../Views/Pages/CourseJobPosition/Create.vue';
import CourseJobPositionEdit from '../Views/Pages/CourseJobPosition/Edit.vue';
import CourseJobPositionDetails from '../Views/Pages/CourseJobPosition/Details.vue';

// Course For Whom
import CourseForWhomLayout from '../Views/Pages/CourseForWhom/Layout.vue';
import CourseForWhomAll from '../Views/Pages/CourseForWhom/All.vue';
import CourseForWhomCreate from '../Views/Pages/CourseForWhom/Create.vue';
import CourseForWhomEdit from '../Views/Pages/CourseForWhom/Edit.vue';
import CourseForWhomDetails from '../Views/Pages/CourseForWhom/Details.vue';

// Course Why Learn
import CourseWhyLearnLayout from '../Views/Pages/CourseWhyLearn/Layout.vue';
import CourseWhyLearnAll from '../Views/Pages/CourseWhyLearn/All.vue';
import CourseWhyLearnCreate from '../Views/Pages/CourseWhyLearn/Create.vue';
import CourseWhyLearnEdit from '../Views/Pages/CourseWhyLearn/Edit.vue';
import CourseWhyLearnDetails from '../Views/Pages/CourseWhyLearn/Details.vue';

// Course Trainer
import CourseTrainerLayout from '../Views/Pages/CourseTrainer/Layout.vue';
import CourseTrainerCreate from '../Views/Pages/CourseTrainer/Create.vue';

// Course Milestones
import CourseMilestoneLayout from '../Views/Pages/CourseMilestone/Layout.vue';
import CourseMilestoneAll from '../Views/Pages/CourseMilestone/All.vue';
import CourseMilestoneCreate from '../Views/Pages/CourseMilestone/Create.vue';

// Course Module Classes
import CourseClassLayout from '../Views/Pages/CourseClass/Layout.vue';
import CourseClassAll from '../Views/Pages/CourseClass/All.vue';
import CourseClassCreate from '../Views/Pages/CourseClass/Create.vue';

// Course FAQ
import CourseFaqLayout from '../Views/Pages/CourseFaq/Layout.vue';
import CourseFaqAll from '../Views/Pages/CourseFaq/All.vue';
import CourseFaqCreate from '../Views/Pages/CourseFaq/Create.vue';
import CourseFaqEdit from '../Views/Pages/CourseFaq/Edit.vue';
import CourseFaqDetails from '../Views/Pages/CourseFaq/Details.vue';

// Course Module
import CourseModuleLayout from '../Views/Pages/CourseModule/Layout.vue';
import CourseModuleAll from '../Views/Pages/CourseModule/All.vue';
import CourseModuleCreate from '../Views/Pages/CourseModule/Create.vue';
import CourseModuleCSV from '../Views/Pages/CourseModule/CsvUpload.vue';
import CourseAtGlance from '../Views/Pages/CourseModule/AtGlance.vue';
import CourseClassQuiz from '../Views/Pages/CourseModule/CourseClassQuiz.vue';

// Course Routines
import CourseRoutines from '../Views/Pages/CourseRoutine/CourseRoutine.vue';

export default [
    {
        path: '/course-details-management',
        name: 'CourseDetailsManagement',
        component: CourseManagementLayout,
        meta: {
            title: 'Course Details Management',
            permissions: ["course_details_management"],
        },
        children: [
            {
                path: '',
                redirect: { name: 'AllCourses' }
            },
            {
                path: 'all-courses',
                name: 'AllCourses',
                component: AllCourse,
            },
            {
                path: 'create-course',
                name: 'CreateCourse', 
                component: CreateCourse,
            },
            {
                path: 'edit-course/:id',
                name: 'EditCourse', 
                component: CreateCourse,
                props: true,
            },
            {
                path: 'course/:id',
                component: CourseDetailsLayout,
                children: [
                    {
                        path: '',
                        redirect: 'overview'
                    },
                    {
                        path: 'overview',
                        name: 'CourseDetails',
                        component: CourseOverview,
                    },
                    {
                        path: 'course-overview',
                        name: 'CourseOverview',
                        component: CourseOverview,
                    },
                    {
                        path: 'course-banner',
                        name: 'CourseBanner',
                        component: CourseBanner,
                    },
                    {
                        path: 'course-module-text',
                        name: 'CourseModuleText',
                        component: CourseModuleText,
                    },
                    {
                        path: 'course-preview',
                        name: 'CoursePreview',
                        component: CoursePreview,
                    },

                    // Course Batch Routes
                    {
                        path: 'batch',
                        component: CourseBatchLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseBatch',
                                component: CourseBatchAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseBatchAll',
                                component: CourseBatchAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseBatchCreate',
                                component: CourseBatchCreate,
                            },
                            {
                                path: 'edit/:batch_id',
                                name: 'CourseBatchEdit',
                                component: CourseBatchEdit,
                            },
                            {
                                path: 'details/:batch_id',
                                name: 'CourseBatchDetails',
                                component: CourseBatchDetails,
                            },
                        ]
                    },

                    // Course What Will Learn Routes
                    {
                        path: 'what-will-learn',
                        component: CourseWhatLearnLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseWhatLearn',
                                component: CourseWhatLearnAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseWhatLearnAll',
                                component: CourseWhatLearnAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseWhatLearnCreate',
                                component: CourseWhatLearnCreate,
                            },
                            {
                                path: 'edit/:itemId',
                                name: 'CourseWhatLearnEdit',
                                component: CourseWhatLearnEdit,
                            },
                            {
                                path: 'details/:itemId',
                                name: 'CourseWhatLearnDetails',
                                component: CourseWhatLearnDetails,
                            },
                        ]
                    },

                    // Course Job Position Routes
                    {
                        path: 'job-position',
                        component: CourseJobPositionLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseJobPosition',
                                component: CourseJobPositionAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseJobPositionAll',
                                component: CourseJobPositionAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseJobPositionCreate',
                                component: CourseJobPositionCreate,
                            },
                            {
                                path: 'edit/:itemId',
                                name: 'CourseJobPositionEdit',
                                component: CourseJobPositionEdit,
                            },
                            {
                                path: 'details/:itemId',
                                name: 'CourseJobPositionDetails',
                                component: CourseJobPositionDetails,
                            },
                        ]
                    },

                    // Course For Whom Routes
                    {
                        path: 'for-whom',
                        component: CourseForWhomLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseForWhom',
                                component: CourseForWhomAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseForWhomAll',
                                component: CourseForWhomAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseForWhomCreate',
                                component: CourseForWhomCreate,
                            },
                            {
                                path: 'edit/:itemId',
                                name: 'CourseForWhomEdit',
                                component: CourseForWhomEdit,
                            },
                            {
                                path: 'details/:itemId',
                                name: 'CourseForWhomDetails',
                                component: CourseForWhomDetails,
                            },
                        ]
                    },

                    // Course Why Learn Routes
                    {
                        path: 'why-learn',
                        component: CourseWhyLearnLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseWhyLearn',
                                component: CourseWhyLearnAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseWhyLearnAll',
                                component: CourseWhyLearnAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseWhyLearnCreate',
                                component: CourseWhyLearnCreate,
                            },
                            {
                                path: 'edit/:itemId',
                                name: 'CourseWhyLearnEdit',
                                component: CourseWhyLearnEdit,
                            },
                            {
                                path: 'details/:itemId',
                                name: 'CourseWhyLearnDetails',
                                component: CourseWhyLearnDetails,
                            },
                        ]
                    },

                    // Course Trainer Routes
                    {
                        path: 'trainer',
                        component: CourseTrainerLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseTrainer',
                                component: CourseTrainerCreate,
                            },
                            {
                                path: 'create',
                                name: 'CourseTrainerCreate',
                                component: CourseTrainerCreate,
                            },
                        ]
                    },

                    // Course Milestone Routes
                    {
                        path: 'milestone',
                        component: CourseMilestoneLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseMilestone',
                                component: CourseMilestoneAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseMilestoneAll',
                                component: CourseMilestoneAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseMilestoneCreate',
                                component: CourseMilestoneCreate,
                            },
                        ]
                    },

                    // Course Class Routes
                    {
                        path: 'class',
                        component: CourseClassLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseClass',
                                component: CourseClassAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseClassAll',
                                component: CourseClassAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseClassCreate',
                                component: CourseClassCreate,
                            },
                        ]
                    },

                    // Course FAQ Routes
                    {
                        path: 'faq',
                        component: CourseFaqLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseFaq',
                                component: CourseFaqAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseFaqAll',
                                component: CourseFaqAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseFaqCreate',
                                component: CourseFaqCreate,
                            },
                            {
                                path: 'edit/:itemId',
                                name: 'CourseFaqEdit',
                                component: CourseFaqEdit,
                            },
                            {
                                path: 'details/:itemId',
                                name: 'CourseFaqDetails',
                                component: CourseFaqDetails,
                            },
                        ]
                    },

                    // Course Module Routes
                    {
                        path: 'module',
                        component: CourseModuleLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseModule',
                                component: CourseModuleAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseModuleAll',
                                component: CourseModuleAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseModuleCreate',
                                component: CourseModuleCreate,
                            },
                            {
                                path: 'csv-upload',
                                name: 'CourseModuleCSV',
                                component: CourseModuleCSV,
                            },
                            {
                                path: 'at-glance',
                                name: 'CourseAtGlance',
                                component: CourseAtGlance,
                            },
                            {
                                path: 'class-quiz',
                                name: 'CourseClassQuiz',
                                component: CourseClassQuiz,
                            },
                        ]
                    },

                    // Course Routine Routes
                    {
                        path: 'routine',
                        name: 'CourseRoutines',
                        component: CourseRoutines,
                    },
                ]
            },
        ]
    },
];
