/**
 * ResumeKu Pro - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    initTemplateSelector();
    initFormListeners();
    initExperienceEducation();
    initFAQ();
    initModal();
    initFormValidation();
});

function initTemplateSelector() {
    const templateCards = document.querySelectorAll('.template-card');
    const templateInput = document.getElementById('selectedTemplate');
    const previewContent = document.getElementById('resumePreview');
    
    templateCards.forEach(card => {
        card.addEventListener('click', function() {
            templateCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            templateInput.value = this.dataset.template;
            previewContent.className = `resume-preview-content ${this.dataset.template}`;
            updatePreview();
        });
    });
}

function initFormListeners() {
    const form = document.getElementById('resumeForm');
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', debounce(updatePreview, 300));
    });
}

function initExperienceEducation() {
    document.getElementById('addExperience')?.addEventListener('click', addExperienceItem);
    document.getElementById('addEducation')?.addEventListener('click', addEducationItem);
}

function addExperienceItem() {
    const container = document.getElementById('experienceList');
    const item = document.createElement('div');
    item.className = 'experience-item';
    item.innerHTML = `
        <div class="form-grid">
            <div class="form-group"><label>Jawatan</label><input type="text" name="exp_title[]" placeholder="Jawatan"></div>
            <div class="form-group"><label>Syarikat</label><input type="text" name="exp_company[]" placeholder="Syarikat"></div>
            <div class="form-group"><label>Tarikh Mula</label><input type="month" name="exp_start[]"></div>
            <div class="form-group"><label>Tarikh Tamat</label><input type="month" name="exp_end[]"></div>
        </div>
        <div class="form-group full-width"><label>Tanggungjawab</label><textarea name="exp_desc[]" rows="3"></textarea></div>
        <button type="button" class="btn btn-small" style="background:rgba(239,68,68,0.2);color:#ef4444;margin-top:10px" onclick="this.parentElement.remove();updatePreview()">Padam</button>
    `;
    container.appendChild(item);
    item.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', debounce(updatePreview, 300)));
}

function addEducationItem() {
    const container = document.getElementById('educationList');
    const item = document.createElement('div');
    item.className = 'education-item';
    item.innerHTML = `
        <div class="form-grid">
            <div class="form-group"><label>Kelayakan</label><input type="text" name="edu_degree[]"></div>
            <div class="form-group"><label>Institusi</label><input type="text" name="edu_school[]"></div>
            <div class="form-group"><label>Tahun</label><input type="number" name="edu_year[]" min="1970" max="2030"></div>
            <div class="form-group"><label>CGPA</label><input type="text" name="edu_grade[]"></div>
        </div>
        <button type="button" class="btn btn-small" style="background:rgba(239,68,68,0.2);color:#ef4444;margin-top:10px" onclick="this.parentElement.remove();updatePreview()">Padam</button>
    `;
    container.appendChild(item);
    item.querySelectorAll('input').forEach(el => el.addEventListener('input', debounce(updatePreview, 300)));
}

function updatePreview() {
    const preview = document.getElementById('resumePreview');
    const form = document.getElementById('resumeForm');
    const fd = new FormData(form);
    
    const data = {
        fullName: fd.get('fullName') || '',
        jobTitle: fd.get('jobTitle') || '',
        email: fd.get('email') || '',
        phone: fd.get('phone') || '',
        location: fd.get('location') || '',
        linkedin: fd.get('linkedin') || '',
        summary: fd.get('summary') || '',
        skills: fd.get('skills') || '',
        languages: fd.get('languages') || ''
    };
    
    if (!data.fullName && !data.jobTitle && !data.email) {
        preview.innerHTML = '<div class="preview-placeholder"><p>Mula isi borang untuk lihat pratonton</p></div>';
        return;
    }
    
    let expHTML = '';
    fd.getAll('exp_title[]').forEach((t, i) => {
        if (t || fd.getAll('exp_company[]')[i]) {
            expHTML += `<div class="exp-item"><div class="exp-header"><span class="exp-title">${esc(t)}</span></div><div class="exp-company">${esc(fd.getAll('exp_company[]')[i])}</div><div class="exp-desc">${esc(fd.getAll('exp_desc[]')[i])}</div></div>`;
        }
    });
    
    let eduHTML = '';
    fd.getAll('edu_degree[]').forEach((d, i) => {
        if (d || fd.getAll('edu_school[]')[i]) {
            eduHTML += `<div class="edu-item"><span class="edu-degree">${esc(d)}</span> - ${esc(fd.getAll('edu_school[]')[i])} (${fd.getAll('edu_year[]')[i]})</div>`;
        }
    });
    
    let skillsHTML = data.skills ? `<div class="skills-list">${data.skills.split(',').map(s => `<span class="skill-tag">${esc(s.trim())}</span>`).join('')}</div>` : '';
    
    preview.innerHTML = `
        <div class="resume-name">${esc(data.fullName)}</div>
        <div class="resume-title">${esc(data.jobTitle)}</div>
        <div class="resume-contact">${[data.email, data.phone, data.location].filter(Boolean).join(' | ')}</div>
        ${data.summary ? `<div class="section-content"><div class="section-title-preview">Ringkasan</div><p>${esc(data.summary)}</p></div>` : ''}
        ${expHTML ? `<div class="section-content"><div class="section-title-preview">Pengalaman</div>${expHTML}</div>` : ''}
        ${eduHTML ? `<div class="section-content"><div class="section-title-preview">Pendidikan</div>${eduHTML}</div>` : ''}
        ${skillsHTML ? `<div class="section-content"><div class="section-title-preview">Kemahiran</div>${skillsHTML}</div>` : ''}
        ${data.languages ? `<div class="section-content"><div class="section-title-preview">Bahasa</div><p>${esc(data.languages)}</p></div>` : ''}
    `;
    
    document.getElementById('resumeDataField').value = JSON.stringify(Object.fromEntries(fd));
}

function initFAQ() {
    document.querySelectorAll('.faq-item').forEach(item => {
        item.querySelector('.faq-question').addEventListener('click', () => {
            document.querySelectorAll('.faq-item').forEach(o => o !== item && o.classList.remove('active'));
            item.classList.toggle('active');
        });
    });
}

function initModal() {
    const modal = document.getElementById('previewModal');
    document.getElementById('previewBtn')?.addEventListener('click', () => {
        document.getElementById('modalPreview').innerHTML = document.getElementById('resumePreview').innerHTML;
        modal.classList.add('active');
    });
    document.getElementById('closeModal')?.addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', e => e.target === modal && modal.classList.remove('active'));
}

function initFormValidation() {
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        const n = document.getElementById('fullName').value.trim();
        const j = document.getElementById('jobTitle').value.trim();
        const m = document.getElementById('email').value.trim();
        if (!n || !j || !m) {
            e.preventDefault();
            alert('Sila isi semua maklumat wajib (*) sebelum meneruskan.');
            return false;
        }
        updatePreview();
    });
}

function debounce(fn, wait) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
}

function esc(s) {
    if (!s) return '';
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}
