export default [
	{
		name: "title",
		label: "Enter your title",
		type: "text",
		value: "",
	},

	{
		name: "description",
		label: "Enter your description",
		type: "textarea",
		value: "",
	},

	{
		name: "tags",
		label: "Enter your tags",
		type: "textarea",
		value: "",
	},

	{
		name: "publish_date",
		label: "Enter your publish date",
		type: "datetime-local",
		value: "",
	},

	{
		name: "thumbnail_image",
		label: "Enter your thumbnail image",
		type: "text",
		value: "",
	},

	{
		name: "images",
		label: "Enter your images",
		type: "textarea",
		value: "",
	},

	{
		name: "blog_type",
		label: "Enter your blog type",
		type: "text",
		value: "",
	},

	{
		name: "url",
		label: "Enter your url",
		type: "text",
		value: "",
	},

	{
		name: "show_top",
		label: "Enter your show top",
		type: "text",
		value: "",
	},

	{
		name: "contributors",
		label: "Enter your contributors",
		type: "textarea",
		placeholder: "Enter JSON data",
		value: "",
	},

	{
		name: "is_featured",
		label: "Enter your is featured",
		type: "select",
		label: "Select is featured",
		multiple: false,
		data_list: [
			{
				label: "Yes",
				value: "1",
			},
			{
				label: "No",
				value: "0",
			},
		],
		value: "",
	},

	{
		name: "is_published",
		label: "Enter your is published",
		type: "select",
		label: "Select is published",
		multiple: false,
		data_list: [
			{
				label: "Yes",
				value: "1",
			},
			{
				label: "No",
				value: "0",
			},
		],
		value: "",
	},
];
