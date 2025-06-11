/**
*    File        : frontend/js/controllers/subjectsController.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

import { subjectsAPI } from '../api/subjectsAPI.js';

document.addEventListener('DOMContentLoaded', () => 
{
    loadSubjects();
    setupSubjectFormHandler();
    setupCancelHandler();


    const sortBtn = document.getElementById('sortSubjectsBtn');
    if (sortBtn) {
        sortBtn.addEventListener('click', () => {
            allSubjects.sort((a, b) => a.name.localeCompare(b.name));
            renderSubjectTable(allSubjects);
        });
    }
});

function setupSubjectFormHandler() 
{
  const form = document.getElementById('subjectForm');
  form.addEventListener('submit', async e => 
  {
        e.preventDefault();
        const subject = 
        {
            id: document.getElementById('subjectId').value.trim(),
            name: document.getElementById('name').value.trim()
        };

        try 
        {
            if (subject.id) 
            {
                await subjectsAPI.update(subject);
            }
            else
            {
                await subjectsAPI.create(subject);
            }
            
            form.reset();
            document.getElementById('subjectId').value = '';
            loadSubjects();
        }
        catch (err)
        {
            alert("No se pudo agregar la materia: " + err.message);
        }
  });
}

function setupCancelHandler()
{
    const cancelBtn = document.getElementById('cancelBtn');
    cancelBtn.addEventListener('click', () => 
    {
        document.getElementById('subjectId').value = '';
    });
}

let allSubjects = [];

async function loadSubjects()
{
    try {
        allSubjects = await subjectsAPI.fetchAll();
        renderSubjectTable(allSubjects);
    }
    catch (err) {
        console.error('Error cargando materias:', err.message);
    }
}


function renderSubjectTable(subjects)
{
    const tbody = document.getElementById('subjectTableBody');
    tbody.replaceChildren();

    subjects.forEach(subject =>
    {
        const tr = document.createElement('tr');

        tr.appendChild(createCell(subject.name));
        tr.appendChild(createSubjectActionsCell(subject));

        tbody.appendChild(tr);
    });
}

function createCell(text)
{
    const td = document.createElement('td');
    td.textContent = text;
    return td;
}

function createSubjectActionsCell(subject)
{
    const td = document.createElement('td');

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'w3-button w3-blue w3-small';
    editBtn.addEventListener('click', () => 
    {
        document.getElementById('subjectId').value = subject.id;
        document.getElementById('name').value = subject.name;
    });

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Borrar';
    deleteBtn.className = 'w3-button w3-red w3-small w3-margin-left';
    deleteBtn.addEventListener('click', () => confirmDeleteSubject(subject.id));

    td.appendChild(editBtn);
    td.appendChild(deleteBtn);
    return td;
}

async function confirmDeleteSubject(id)
{
    if (!confirm('¿Seguro que deseas borrar esta materia?')) return;

    try
    {
        await subjectsAPI.remove(id);
        loadSubjects();
    }
   
catch (err) {
    alert("No se pudo borrar la materia: " + err.message);
    console.error('Error al borrar materia:', err.message);
}


function showAlert(message) {
    const container = document.getElementById('alertContainer');
    if (!container) return;

    container.innerText = message;
    container.style.display = 'block';
    container.style.backgroundColor = '#ffcdd2';
    container.style.color = '#b71c1c';
    container.style.padding = '10px';
    container.style.border = '1px solid #b71c1c';
    container.style.borderRadius = '5px';
    container.style.fontWeight = 'bold';
    container.style.textAlign = 'center';

    setTimeout(() => {
        container.style.display = 'none';
    }, 5000);
}


}
