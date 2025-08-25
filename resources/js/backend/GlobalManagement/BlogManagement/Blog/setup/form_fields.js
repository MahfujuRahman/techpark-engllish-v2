export default [
  {
    name: "title",
    label: "Enter your title",
    type: "text",
    value: "",
  },
  {
    name: "publish_date",
    label: "Enter your publish date",
    type: "datetime-local",
    value: "",
  },

  {
    name: "url",
    label: "Enter your url",
    type: "url",
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
  {
    name: "description",
    label: "Enter your description",
    type: "textarea",
    value: "",
  },

  {
    name: "images",
    label: "Enter your images",
    type: "file",
    value: "",
  },
];
