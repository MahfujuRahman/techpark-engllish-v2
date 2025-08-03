
import app_config from "@config/app_config";
import setup_type from "./setup_type";

const prefix: string = "SeminerParticipant";

const setup: setup_type = {
    prefix,
    permission: ["admin", "super_admin"],

    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: "seminer-participants",

    module_name: "seminer-participant",
    store_prefix: "seminer_participant",
    route_prefix: "SeminerParticipant",
    route_path: "seminer-participant",

    select_fields: [
        "id",
        "seminar_id",
            "full_name",
            "email",
            "phone_number",
            "address",
        "status",
        "slug",
        "created_at",
        'deleted_at'
    ],

    sort_by_cols: [
        "id",
        "seminar_id",
            "full_name",
            "email",
            "phone_number",
            "address",
        "status",
        "created_at",
    ],
    table_header_data: [
        "id",
        "seminar_id",
            "full_name",
            "email",
            "phone_number",
            "address",
        "status",
        "created_at",
    ],
    table_row_data: [
        "id",
        "seminar_id",
            "full_name",
            "email",
            "phone_number",
            "address",
        "status",
        "created_at",
    ],
    quick_view_data: [
        "id",
        "seminar_id",
            "full_name",
            "email",
            "phone_number",
            "address",
        "status",
        "created_at",
    ],

    layout_title: prefix + " Management",
    page_title: `${prefix} Management`,

    all_page_title: "All " + prefix,
    details_page_title: "Details " + prefix,
    create_page_title: "Create " + prefix,
    edit_page_title: "Edit " + prefix,
};

export default setup;
