export default [
	{
		name: "full_name",
		label: "Enter your full name",
		type: "text",
		value: "",
	},

	{
		name: "email",
		label: "Enter your email",
		type: "text",
		value: "",
	},

	{
		name: "subject",
		label: "Enter your subject",
		type: "text",
		value: "",
	},

	{
		name: "message",
		label: "Enter your message",
		type: "textarea",
		value: "",
	},

	{
		name: "is_seen",
		label: "Enter your is seen",
		type: "select",
		label: "Select is seen",
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
