function form_input_html(label_text, required, input_name, checkmark_id, wrapper_id, placeholder="", input_type="text"){
    const required_asterisk = required?"*":" ";
    const input_row_html = `
                <div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <input type="${input_type}" name="${input_name}" class="form-control form-control-normal" placeholder="${placeholder}">
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>`;
    return input_row_html
}
function form_search_button_html(label_text, required, input_name, checkmark_id, wrapper_id, button_id, invisible_input_id, placeholder=""){
    const required_asterisk = required?"*":" ";
    const input_row_html = `
                <div style="display: flex; flex-direction: row;" id=${wrapper_id}>
                    <label class="password-change-star-label">${required_asterisk}</label>
                    <label class="password-change-input-label">${label_text}</label>
                    <input name="${input_name}" id="${invisible_input_id}" class="hidden">
                    <button type="button" name="${input_name}_button" id ="${button_id}" class="form-control form-control-normal btn-primary btn btn_generic_search_form">${placeholder}
                    </button>
                    <label id="${checkmark_id}" class="password-change-mark-label" style="color: green;"></label>
                </div>`;
    return input_row_html
}
function space_html(id=""){
    const id_string = id == "" ? "" : `id='${id}'`;
    const space_html = `<div style="margin: 1rem;" ${id_string}></div>`;
    return space_html
}
function owner_input_radio_html(prefix, suffix){
    return `
        <div style="display: flex; flex-direction: row;">
            <label class = "password-change-star-label">*</label>
                            <label class = "password-change-input-label">Ownership</label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="${prefix}${suffix}" value="select_existing"> Select Existing
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="${prefix}${suffix}" value="input_new"> Input New
            </label>
            <label style="margin-right: 1rem;">
                <input type="radio" name="${prefix}${suffix}" value="leave_it_empty" checked> Leave it empty
            </label>
        </div>`
}
function display_form_elements(prefix, suffixes =["owner_name","new_owner_space_1","owner_address","new_owner_space_2","owner_licence"] , display = true){
    for(var i = 0; i < suffixes.length; i++) {
        const suffix = suffixes[i];
        const owner_name = document.getElementById(`${prefix}${suffix}`);
        if (owner_name) {
            if(display && owner_name.classList.contains('hidden')) {
                owner_name.classList.remove("hidden");
            }
            else{
                owner_name.classList.add("hidden");
            }
        }
    }
}
