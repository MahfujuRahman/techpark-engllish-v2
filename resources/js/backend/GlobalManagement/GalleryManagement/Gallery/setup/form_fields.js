export default [
  {
    name: "title",
    label: "Enter your title",
    type: "text",
    value: "",
  },
  {
    name: "top_image",
    label: "Enter your top image",
    type: "select",
    label: "Select top image",
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
    name: "image",
    label: "Enter your image",
    type: "file",
    value: "",
  },
  {
    name: "description",
    label: "Enter your description",
    type: "textarea",
    value: "",
    row_col_class: "col-12",
  },
];
