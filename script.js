document.addEventListener('DOMContentLoaded', function () {
    initTemplateSelection();
    initFormListeners();
    initAddButtons();
    updatePreview();
});

function initTemplateSelection() {
    document.querySelectorAll('.template-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.template-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            updatePreview();
        });
    });
}

function initFormListeners() {
    document.querySelectorAll('#resumeForm input, #resumeForm textarea').forEach(el => {
        el.addEventListener('input', debounce(updatePreview, 300));
    });
    document.getElementById('refreshPreview')?.addEventListener('click', updatePreview);
}

function initAddButtons() {
    document.getElementById('addExperience')?.addEventListener('click', () => {
        const container = document.getElementById('experienceList');
        const item = document.createElement('div');
        item.className = 'experience-item';
        item.innerHTML = `
            <button type="button" class="remove-btn" onclick="this.parentElement.remove();updatePreview();">×</button>
            <div class="form-grid">
                <div class="form-group"><label>Jawatan</label><input type="text" name="job_title[]" placeholder="Jawatan"></div>
                <div class="form-group"><label>Syarikat</label><input type="text" name="company[]" placeholder="Syarikat"></div>
                <div class="form-group"><label>Tarikh Mula</label><input type="text" name="start_date[]" placeholder="Jan 2020"></div>
                <div class="form-group"><label>Tarikh Tamat</label><input type="text" name="end_date[]" placeholder="Kini"></div>
                <div class="form-group full"><label>Tanggungjawab</label><textarea name="responsibilities[]" rows="2"></textarea></div>
            </div>
        `;
        container.appendChild(item);
        item.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', debounce(updatePreview, 300)));
    });

    document.getElementById('addEducation')?.addEventListener('click', () => {
        const container = document.getElementById('educationList');
        const item = document.createElement('div');
        item.className = 'education-item';
        item.innerHTML = `
            <button type="button" class="remove-btn" onclick="this.parentElement.remove();updatePreview();">×</button>
            <div class="form-grid">
                <div class="form-group"><label>Kelayakan</label><input type="text" name="qualification[]" placeholder="Kelayakan"></div>
                <div class="form-group"><label>Institusi</label><input type="text" name="institution[]" placeholder="Institusi"></div>
                <div class="form-group"><label>Tahun</label><input type="text" name="edu_year[]" placeholder="2020"></div>
            </div>
        `;
        container.appendChild(item);
        item.querySelectorAll('input').forEach(el => el.addEventListener('input', debounce(updatePreview, 300)));
    });
}

function updatePreview() {
    const preview = document.getElementById('resumePreview');
    const form = document.getElementById('resumeForm');
    const data = new FormData(form);

    const fullname = data.get('fullname') || '';
    const email = data.get('email') || '';
    const phone = data.get('phone') || '';
    const location = data.get('location') || '';
    const summary = data.get('summary') || '';
    const skills = data.get('skills') || '';

    const jobs = data.getAll('job_title[]');
    const companies = data.getAll('company[]');
    const startDates = data.getAll('start_date[]');
    const endDates = data.getAll('end_date[]');
    const responsibilities = data.getAll('responsibilities[]');

    const qualifications = data.getAll('qualification[]');
    const institutions = data.getAll('institution[]');
    const eduYears = data.getAll('edu_year[]');

    if (!fullname && !email) {
        preview.innerHTML = '<div class="preview-placeholder"><span>📄</span><p>Isi maklumat untuk lihat pratonton</p></div>';
        return;
    }

    let experienceHtml = '';
    jobs.forEach((job, i) => {
        if (job || companies[i]) {
            experienceHtml += `
                <div class="exp-item">
                    <div class="exp-title">${job || 'Jawatan'}</div>
                    <div class="exp-company">${companies[i] || ''} | ${startDates[i] || ''} - ${endDates[i] || ''}</div>
                    <div class="exp-desc">${responsibilities[i] || ''}</div>
                </div>
            `;
        }
    });

    let educationHtml = '';
    qualifications.forEach((qual, i) => {
        if (qual || institutions[i]) {
            educationHtml += `<div class="edu-item"><strong>${qual || ''}</strong> - ${institutions[i] || ''} (${eduYears[i] || ''})</div>`;
        }
    });

    preview.innerHTML = `
        <div class="resume-header">
            <h2>${fullname || 'Nama Anda'}</h2>
            <p>${[email, phone, location].filter(Boolean).join(' | ')}</p>
        </div>
        ${summary ? `<div class="resume-section"><h3>Ringkasan</h3><p>${summary}</p></div>` : ''}
        ${experienceHtml ? `<div class="resume-section"><h3>Pengalaman</h3>${experienceHtml}</div>` : ''}
        ${educationHtml ? `<div class="resume-section"><h3>Pendidikan</h3>${educationHtml}</div>` : ''}
        ${skills ? `<div class="resume-section"><h3>Kemahiran</h3><p>${skills}</p></div>` : ''}
    `;

    // Save data to localStorage for after payment
    saveResumeData();
}

function saveResumeData() {
    const form = document.getElementById('resumeForm');
    const data = new FormData(form);
    const resumeData = {};
    for (let [key, value] of data.entries()) {
        if (key.endsWith('[]')) {
            const k = key.slice(0, -2);
            if (!resumeData[k]) resumeData[k] = [];
            resumeData[k].push(value);
        } else {
            resumeData[key] = value;
        }
    }
    localStorage.setItem('resumeData', JSON.stringify(resumeData));
}

function debounce(fn, wait) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
}
