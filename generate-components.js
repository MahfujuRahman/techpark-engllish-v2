const fs = require('fs');
const path = require('path');

const basePath = '/home/ajmain/Desktop/techparkenglish/TechPark English/resources/js/backend/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Views/Pages';

// Component structure mapping
const components = {
    'CourseWhatLearn': {
        Create: 'শিক্ষণীয় বিষয় তৈরি',
        Edit: 'শিক্ষণীয় বিষয় সম্পাদনা', 
        Details: 'শিক্ষণীয় বিষয়ের বিস্তারিত'
    },
    'CourseJobPosition': {
        Layout: 'জব পজিশন লেআউট',
        All: 'সব জব পজিশন',
        Create: 'জব পজিশন তৈরি',
        Edit: 'জব পজিশন সম্পাদনা',
        Details: 'জব পজিশনের বিস্তারিত'
    },
    'CourseForWhom': {
        Layout: 'কাদের জন্য লেআউট',
        All: 'সব তালিকা',
        Create: 'নতুন যোগ করুন',
        Edit: 'সম্পাদনা করুন',
        Details: 'বিস্তারিত তথ্য'
    },
    'CourseWhyLearn': {
        Layout: 'কেন শিখবেন লেআউট',
        All: 'সব কারণ',
        Create: 'নতুন কারণ যোগ করুন',
        Edit: 'কারণ সম্পাদনা',
        Details: 'কারণের বিস্তারিত'
    },
    'CourseTrainer': {
        Layout: 'ট্রেনার লেআউট',
        Create: 'ট্রেনার যোগ করুন'
    },
    'CourseMilestone': {
        Layout: 'মাইলস্টোন লেআউট',
        All: 'সব মাইলস্টোন',
        Create: 'মাইলস্টোন তৈরি'
    },
    'CourseClass': {
        Layout: 'ক্লাস লেআউট',
        All: 'সব ক্লাস',
        Create: 'ক্লাস তৈরি'
    },
    'CourseFaq': {
        Layout: 'FAQ লেআউট',
        All: 'সব FAQ',
        Create: 'FAQ তৈরি',
        Edit: 'FAQ সম্পাদনা',
        Details: 'FAQ বিস্তারিত'
    },
    'CourseModule': {
        Layout: 'মডিউল লেআউট',
        All: 'সব মডিউল',
        Create: 'মডিউল তৈরি',
        CsvUpload: 'CSV আপলোড',
        AtGlance: 'এক নজরে',
        CourseClassQuiz: 'ক্লাস কুইজ'
    }
};

// Add missing CourseWhatLearn components
components.CourseWhatLearn.Create = 'শিক্ষণীয় বিষয় তৈরি';
components.CourseWhatLearn.Edit = 'শিক্ষণীয় বিষয় সম্পাদনা';
components.CourseWhatLearn.Details = 'শিক্ষণীয় বিষয়ের বিস্তারিত';

// Generate basic template
function generateTemplate(componentName, type, title) {
    const isLayout = type === 'Layout';
    const hasRouter = isLayout;
    
    return `<template>
    <div class="${componentName.toLowerCase().replace(/([A-Z])/g, '-$1').substring(1)}-${type.toLowerCase()}">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-${getIcon(type)} mr-2"></i>
                    ${title}
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    ${title} - API কল পরে যোগ করা হবে। এটি একটি প্রাথমিক টেমপ্লেট।
                </div>
                
                ${isLayout ? '<router-view />' : `
                <!-- Content will be implemented here -->
                <div class="placeholder-content">
                    <p>এই পেইজটি এখনো সম্পূর্ণ হয়নি। শীঘ্রই আপডেট করা হবে।</p>
                </div>`}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: '${componentName}${type}',
    
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
        console.log('${componentName}${type} component mounted');
    },
};
</script>

<style scoped>
.${componentName.toLowerCase().replace(/([A-Z])/g, '-$1').substring(1)}-${type.toLowerCase()} {
    max-width: 100%;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.alert-info {
    border-left: 4px solid #17a2b8;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.placeholder-content {
    text-align: center;
    padding: 40px 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-top: 20px;
}
</style>`;
}

function getIcon(type) {
    const icons = {
        Layout: 'th-large',
        All: 'list',
        Create: 'plus',
        Edit: 'edit',
        Details: 'info-circle',
        CsvUpload: 'file-csv',
        AtGlance: 'eye',
        CourseClassQuiz: 'question-circle'
    };
    return icons[type] || 'circle';
}

// Create directories and files
Object.entries(components).forEach(([componentName, types]) => {
    const dirPath = path.join(basePath, componentName);
    
    // Create directory if it doesn't exist
    if (!fs.existsSync(dirPath)) {
        fs.mkdirSync(dirPath, { recursive: true });
        console.log(`Created directory: ${dirPath}`);
    }
    
    Object.entries(types).forEach(([type, title]) => {
        const filePath = path.join(dirPath, `${type}.vue`);
        
        // Only create if file doesn't exist
        if (!fs.existsSync(filePath)) {
            const template = generateTemplate(componentName, type, title);
            fs.writeFileSync(filePath, template);
            console.log(`Created component: ${filePath}`);
        } else {
            console.log(`Skipped existing: ${filePath}`);
        }
    });
});

// Create CourseRoutine component
const routineDir = path.join(basePath, 'CourseRoutine');
if (!fs.existsSync(routineDir)) {
    fs.mkdirSync(routineDir, { recursive: true });
}

const routineFile = path.join(routineDir, 'CourseRoutine.vue');
if (!fs.existsSync(routineFile)) {
    const routineTemplate = generateTemplate('CourseRoutine', 'CourseRoutine', 'কোর্স রুটিন');
    fs.writeFileSync(routineFile, routineTemplate);
    console.log(`Created component: ${routineFile}`);
}

console.log('All component templates generated successfully!');
