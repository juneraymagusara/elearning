var courses = [];
function renderLanding() {
    document.body.style.backgroundImage = "url('../img/bg.jpg')";
    document.body.style.backgroundRepeat = "no-repeat";
    document.getElementById("header").innerHTML = "";
    document.getElementById("content").innerHTML = "\
        <h1 id=\"gs-header\">E-Learning</h1> \
        <span id=\"gs-tag\">Your study buddy.</span>\
        <div class=\"feature\">\
            <ul>\
            <li>Organize e-materials</li>\
            <li>Customize e-materials</li>\
            </ul>\
            <div class=\"gs-btn-area\">\
                <button class=\"btn primary\" onclick=\"getCourses(true,true)\">Get Started</button>\
            </div>\
        </div>\
        \
        ";
}

function renderHeader(editMode=false, admin=false, detail=false) {
    document.body.style.backgroundImage = "url('')";
    document.getElementById("header").innerHTML = admin ? "Administration":"My Courses";
    const btn = renderButton("Add Course", "pull-right btn primary");

    btn.addEventListener('click', (event) => {
        renderDetail([],editMode);
    });

    editMode && !detail? document.getElementById("header").appendChild(btn):"";
    document.getElementById("content").innerHTML = "<hr>";
}

function renderList(list, editMode=false, admin=false) {
    renderHeader(editMode, admin);

    const element = document.getElementById("content");

    if(list.records.length == 0 && admin) {
        element.appendChild(renderText("p", "No records found.", "align-center "));
    }
    
    for(var i=0; i<list.records.length; i++){
        if (i%4 == 0) {
            var cards = renderElement("div", "flex-container")
            element.appendChild(cards);
        } else {
            var cards = element.lastChild;
        }

        const actions = renderElement("div", "pull-right")
        const card = renderElement("div", "card");
        const contain = renderElement("div", "container");
        const btnArea = renderElement("div");
        const updateBtn = renderElement("i", "fa fa-pencil btn-update");
        const deleteBtn = renderElement("i", "fa fa-times btn-delete");

        editMode ? deleteBtn.appendChild(renderText("p",list.records[i].fcourseid,"hide")) : "";
        editMode ? updateBtn.appendChild(renderText("p",list.records[i].fcourseid,"hide")) : "";
        actions.appendChild(updateBtn);
        actions.appendChild(deleteBtn);

        btnArea.appendChild(actions);
        // editMode ? contain.appendChild(renderText("h2", list.records[i].fcourseid + ". " + list.records[i].fcourse_title)).appendChild(actions) : 
        editMode ? contain.appendChild(btnArea) : "";
        contain.appendChild(renderText("h2", list.records[i].fcourseid + ". " + list.records[i].fcourse_title));
        contain.appendChild(document.createElement("hr"));
        contain.appendChild(renderTextWithLabel("Author: ", list.records[i].fauthor));
        contain.appendChild(renderTextWithLabel("Description: ", list.records[i].fcourse_memo));

        card.appendChild(contain);
        card.appendChild(renderText("p",list.records[i].fcourseid,"hide"));
        cards.appendChild(card);
    }  

    editMode ? "": document.querySelectorAll('.card ').forEach(item => {
        item.addEventListener('click', event => {
            id = item.querySelector('.hide').textContent;
            var obj = {
                fcourseid: id
            }
            getOneCourse(obj);
        })
    });

    document.querySelectorAll('.btn-delete').forEach(item => {
        item.addEventListener('click', event => {
            id = item.querySelector('.hide').textContent;
            var obj = {
                fcourseid: id
            }
            if(confirm('Are you sure you want to delete course id '+ id +'?')){
                deleteCourse(obj, editMode, true);
            }
        })
    });

    document.querySelectorAll('.btn-update').forEach(item => {
        item.addEventListener('click', event => {
            id = item.querySelector('.hide').textContent;
            var obj = {
                fcourseid: id
            }
            getOneCourse(obj,false,true,true);
        })
    });
}

function renderDetail(list, editMode=false, update=false) {
    const element = document.getElementById("content");
    const formArea = renderElement("div", "form-area");
    const btnArea = renderElement("div", "btn-area");
    const submitBtn = renderButton(update ? "Update" : "Add", "btn primary");
    const cancelBtn = renderButton("Cancel", "btn warning");

    submitBtn.addEventListener('click', (event) => {
        if(update) {
            if(document.getElementById('fcourse_title').value == '' || 
            document.getElementById('fauthor').value == '' ||
            document.getElementById('fcourse_memo').value == '') {
                alert("Fields cannot be blank!");
            } else {
                var data = {
                    fcourseid: list.fcourseid,
                    fcourse_title: document.getElementById('fcourse_title').value, 
                    fcourse_memo: document.getElementById('fcourse_memo').value, 
                    fauthor: document.getElementById('fauthor').value
                }
                updateCourse(data);
            }
        } else { 
            if(document.getElementById('fcourse_title').value == '' || 
            document.getElementById('fauthor').value == '' ||
            document.getElementById('fcourse_memo').value == '') {
                alert("Fields cannot be blank!");
            } else {
                var data = {
                    fcourse_title: document.getElementById('fcourse_title').value, 
                    fcourse_memo: document.getElementById('fcourse_memo').value, 
                    fauthor: document.getElementById('fauthor').value
                }
                getOneCourse(data, true);
            }
            
        
        };
    });
    
    cancelBtn.addEventListener('click', (event) => {
        if(document.getElementById('fcourse_title').value != '' || 
        document.getElementById('fauthor').value != '' ||
        document.getElementById('fcourse_memo').value != '')
        {
            if(confirm('There are unsaved data. Do you wish to proceed cancel?')){
                getCourses(true,true);
            }
        }
        else {
            getCourses(true,true);
        }
    });
    renderHeader(editMode, editMode, true);

    element.appendChild(formArea);
    const bk = renderElement("i", "fa fa-chevron-left btn-bk");
    bk.addEventListener('click', (event) => {
        getCourses(false,false);
    })

    update || editMode ? "" : formArea.appendChild(bk);
    formArea.appendChild(renderText("h3", editMode ? update ? "Update Course" : "Add Course" : "Course Detail"));
    editMode ? "" : formArea.appendChild(renderText("b", "Course ID"));
    editMode ? "" : formArea.appendChild(renderInputFields("fcourseid", list != undefined ? list.fcourseid : "", editMode));
    formArea.appendChild(renderText("b", "Course Title"));
    formArea.appendChild(renderInputFields("fcourse_title", list != undefined ? list.fcourse_title : "", editMode));
    formArea.appendChild(renderText("b", "Author"));
    formArea.appendChild(renderInputFields("fauthor", list != undefined ? list.fauthor : "", editMode));
    formArea.appendChild(renderText("b", "Description"));
    formArea.appendChild(renderInputFields("fcourse_memo", list != undefined ? list.fcourse_memo : "", editMode));
    editMode ? "" : formArea.appendChild(renderText("b", "Created Date"));
    editMode ? "" : formArea.appendChild(renderInputFields("fcreated_date", list != undefined ? list.fcreated_date : "", editMode));
    editMode ? "" : formArea.appendChild(renderText("b", "Modified Date"));
    editMode ? "" : formArea.appendChild(renderInputFields("fupdated_date", list != undefined ? list.fupdated_date : "", editMode));

    editMode ? formArea.appendChild(btnArea) : "";
    btnArea.appendChild(submitBtn);
    btnArea.appendChild(cancelBtn);
}

function renderButton(str, classN="") {
    btn = document.createElement("button");
    btn.innerHTML = str;
    btn.className = classN;

    return btn;
}

function renderElement(el, classN="") {
    const element = document.createElement(el);
    element.className = classN;

    return element;
}

function renderText(el, data, classN="") {
    const textEl = document.createElement(el);
    const textStr = document.createTextNode(data);
    textEl.className = classN;

    textEl.appendChild(textStr);

    return textEl;
}

function renderTextWithLabel(label, data) {
    const element = document.createElement("b");
    const labelStr = document.createTextNode(label); 
    const desc = document.createElement("p");
    const descData = document.createTextNode(data);

    element.appendChild(labelStr);
    desc.appendChild(element);
    desc.appendChild(descData);

    return desc;
}

function renderInputFields(label, val, editMode=false) {
    const input = document.createElement("input");
    input.setAttribute("id",label);
    input.disabled = !editMode;
    input.value= val == undefined ? "" : val;

    return input;
}


// API connection starts here
async function getCourses(editMode=false, admin=false) {
    let response = await fetch('http://localhost/api/courses/read.php');
    let data = await response.json();
    courses = data;

    if((courses.records.length == 0 || courses == null) && !admin) {
        renderLanding();
    } else {
        renderList(courses,editMode,admin);
    }
}

async function postData(data = {}) {    
    url = 'http://localhost/api/courses/create.php'; 
    const response = await fetch(url, {
        method: 'POST', 
        body: JSON.stringify(data) 
    });

    var res = await response.json();
    if(res.success) {
        alert(res.message);
        getCourses(true, true);
    }
  }


async function getOneCourse(data = {}, add=false, editMode=false, update=false) {
    url = 'http://localhost/api/courses/read_one.php'; 
    const response = await fetch(url, {
    method: 'POST', 
    body: JSON.stringify(data) 
    });

    var res = await response.json();
    if(add) {
        if(!res.error) {
            alert("Course already existed");
        } else {
            postData(data);
        }
    } else {
        renderDetail(res,editMode,update)
    }
    
}

async function updateCourse(data = {}) {
    url = 'http://localhost/api/courses/update.php'; 
    const response = await fetch(url, {
    method: 'POST', 
    body: JSON.stringify(data) 
    });

    var res = await response.json();
    console.log(data);
    if(res.success) {
        alert(res.message);
        getOneCourse(data, false, false);
    } else {
        alert(res.message);
    }
}

async function deleteCourse(data = {}) {
    url = 'http://localhost/api/courses/delete.php'; 
    const response = await fetch(url, {
    method: 'POST', 
    body: JSON.stringify(data) 
    });

    var res = await response.json();
    console.log(res);
    if(res.success) {
        alert(res.message);
        getCourses(true, true);
    } else {
        alert(res.message);
    }
}