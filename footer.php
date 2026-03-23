<footer>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1;"></div>
        <div style="text-align: center;">
            <a href="terms_conditions.php" style="color: black; text-decoration: none; text-alignment: center;">Terms &
                Conditions</a> ｜
            <a href="privacy_policy.php" style="color: black; text-decoration: none; text-alignment: center;">Privacy
                Policy</a>
        </div>
        <div style="flex: 1; text-align: right; padding-right: 15px;">
            <?php if (isLoggedIn()): ?>
            <form method="post" action="set_font_scale.php" style="display: inline;">
                <label for="fontScaleSelect" style="font-size: 14px;">Text Size:</label>
                <select name="scale" id="fontScaleSelect" onchange="this.form.submit()"
                    style="padding: 4px 8px; border-radius: 4px;">
                    <option value="2.00" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 2.00 ? 'selected' : ''; ?>>200%
                    </option>
                    <option value="1.75" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.75 ? 'selected' : ''; ?>>175%
                    </option>
                    <option value="1.50" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.50 ? 'selected' : ''; ?>>150%
                    </option>
                    <option value="1.25" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.25 ? 'selected' : ''; ?>>125%
                    </option>
                    <option value="1.00" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.00 ? 'selected' : ''; ?>>100%
                    </option>
                </select>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <button id="accessibility-trigger" aria-label="Open accessibility settings">Access</button>

    <div id="accessibility-overlay"></div>

    <div id="accessibility-panel">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Accessibility Settings</h2>
        <button id="accessibility-close" style="background:none;border:none;cursor:pointer;font-size:1.2rem;">Close</button>
      </div>

      <div class="accessibility-section-label">Vision</div>

      <div class="accessibility-row">
        Dark mode
        <label class="toggle-switch">
          <input type="checkbox" id="toggle-dark">
          <span class="toggle-track"></span>
        </label>
      </div>

      <div class="accessibility-row">
        High contrast
        <label class="toggle-switch">
          <input type="checkbox" id="toggle-contrast">
          <span class="toggle-track"></span>
        </label>
      </div>

      <div class="accessibility-section-label">Text</div>

      <div class="accessibility-row" style="flex-direction:column;align-items:flex-start;gap:8px;">
        Text size
        <div class="font-btns">
          <button class="font-btn" data-size="0.85">Small</button>
          <button class="font-btn active" data-size="1">Default</button>
          <button class="font-btn" data-size="1.15">Large</button>
          <button class="font-btn" data-size="1.3">XL</button>
        </div>
      </div>

      <div class="accessibility-row">
        Dyslexia-friendly font
        <label class="toggle-switch">
          <input type="checkbox" id="toggle-dyslexia">
          <span class="toggle-track"></span>
        </label>
      </div>

      <div class="accessibility-section-label">Motion and Focus</div>

      <div class="accessibility-row">
        Reduce motion
        <label class="toggle-switch">
          <input type="checkbox" id="toggle-motion">
          <span class="toggle-track"></span>
        </label>
      </div>

      <div class="accessibility-row">
        Enhanced focus
        <label class="toggle-switch">
          <input type="checkbox" id="toggle-focus">
          <span class="toggle-track"></span>
        </label>
      </div>

      <button id="accessibility-reset">Reset to defaults</button>
    </div>

    <style>
    :root {
      --accent: #ffee32;
      --bg: #ffffff;
      --surface: #f7f7f5;
      --border: #e5e5e3;
      --text: #1a1a1a;
      --text-muted: #6b6b6b;
    }

    body.dark-mode {
      --bg: #111111;
      --surface: #1e1e1e;
      --border: #2e2e2e;
      --text: #f0f0f0;
      --text-muted: #9a9a9a;
    }

    body.high-contrast {
      --bg: #000000;
      --surface: #111111;
      --border: #ffffff;
      --text: #ffffff;
      --text-muted: #dddddd;
      --accent: #ffff00;
    }

    html { font-size: calc(16px * var(--font-scale, 1)); }

    body {
      background: var(--bg);
      color: var(--text);
      transition: background 0.3s, color 0.3s;
    }

    body.reduce-motion *,
    body.reduce-motion *::before,
    body.reduce-motion *::after {
      animation-duration: 0.01ms !important;
      transition-duration: 0.01ms !important;
    }

    body.focus-highlight *:focus {
      outline: 3px solid var(--accent) !important;
      outline-offset: 4px !important;
    }

    body.dyslexia-font {
      font-family: 'Comic Sans MS', cursive !important;
      letter-spacing: 0.05em;
      word-spacing: 0.1em;
    }

    body.spacing-compact { line-height: 1.4; }
    body.spacing-comfortable { line-height: 1.8; }
    body.spacing-relaxed { line-height: 2; }

    #accessibility-trigger {
      position: fixed;
      bottom: 80px;
      right: 28px;
      z-index: 1000;
      padding: 12px 18px;
      border-radius: 999px;
      background: var(--accent);
      border: none;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      box-shadow: 0 4px 20px rgba(0,0,0,0.18);
    }

    #accessibility-trigger:hover {
      background: #e0d129;
    }

    #accessibility-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.35);
      z-index: 1001;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s;
    }

    #accessibility-overlay.open {
      opacity: 1;
      pointer-events: all;
    }

    #accessibility-panel {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: 320px;
      background: var(--bg);
      border-left: 1px solid var(--border);
      z-index: 1002;
      display: flex;
      flex-direction: column;
      transform: translateX(100%);
      transition: transform 0.3s;
      box-shadow: -8px 0 40px rgba(0,0,0,0.12);
      padding: 24px;
      gap: 12px;
      overflow-y: auto;
    }

    #accessibility-panel.open {
      transform: translateX(0);
    }

    #accessibility-panel h2 {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text);
    }

    .accessibility-section-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-top: 8px;
    }

    .accessibility-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 14px;
      border-radius: 10px;
      background: var(--surface);
      border: 1px solid var(--border);
      font-size: 0.9rem;
      color: var(--text);
    }

    .toggle-switch {
      position: relative;
      width: 42px;
      height: 24px;
      flex-shrink: 0;
    }

    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
      position: absolute;
    }

    .toggle-track {
      position: absolute;
      inset: 0;
      border-radius: 999px;
      background: var(--border);
      cursor: pointer;
      transition: background 0.2s;
    }

    .toggle-track::after {
      content: '';
      position: absolute;
      top: 3px;
      left: 3px;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #fff;
      transition: transform 0.2s;
      box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    .toggle-switch input:checked + .toggle-track {
      background: #111;
    }

    .toggle-switch input:checked + .toggle-track::after {
      transform: translateX(18px);
    }

    .font-btns {
      display: flex;
      gap: 8px;
      margin-top: 8px;
    }

    .font-btn {
      flex: 1;
      padding: 8px;
      border-radius: 8px;
      border: 1.5px solid var(--border);
      background: var(--bg);
      cursor: pointer;
      font-size: 0.8rem;
      color: var(--text);
    }

    .font-btn:hover {
      border-color: var(--accent);
    }

    .font-btn.active {
      background: #111;
      color: #fff;
      border-color: #111;
    }

    #accessibility-reset {
      margin-top: auto;
      padding: 10px;
      border-radius: 10px;
      border: 1.5px dashed var(--border);
      background: transparent;
      color: var(--text-muted);
      cursor: pointer;
      font-size: 0.82rem;
      width: 100%;
    }

    #accessibility-reset:hover {
      color: var(--text);
      border-color: var(--text-muted);
    }
    </style>

    <script>
    const trigger = document.getElementById('accessibility-trigger');
    const panel = document.getElementById('accessibility-panel');
    const overlay = document.getElementById('accessibility-overlay');
    const closeBtn = document.getElementById('accessibility-close');
    const resetBtn = document.getElementById('accessibility-reset');

    trigger.addEventListener('click', () => {
      panel.classList.toggle('open');
      overlay.classList.toggle('open');
    });

    closeBtn.addEventListener('click', () => {
      panel.classList.remove('open');
      overlay.classList.remove('open');
    });

    overlay.addEventListener('click', () => {
      panel.classList.remove('open');
      overlay.classList.remove('open');
    });

    document.getElementById('toggle-dark').addEventListener('change', function() {
      document.body.classList.toggle('dark-mode', this.checked);
      save();
    });

    document.getElementById('toggle-contrast').addEventListener('change', function() {
      document.body.classList.toggle('high-contrast', this.checked);
      save();
    });

    document.getElementById('toggle-dyslexia').addEventListener('change', function() {
      document.body.classList.toggle('dyslexia-font', this.checked);
      save();
    });

    document.getElementById('toggle-motion').addEventListener('change', function() {
      document.body.classList.toggle('reduce-motion', this.checked);
      save();
    });

    document.getElementById('toggle-focus').addEventListener('change', function() {
      document.body.classList.toggle('focus-highlight', this.checked);
      save();
    });

    document.querySelectorAll('.font-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.font-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.documentElement.style.setProperty('--font-scale', btn.dataset.size);
        save();
      });
    });

    resetBtn.addEventListener('click', () => {
      document.body.classList.remove('dark-mode', 'high-contrast', 'dyslexia-font', 'reduce-motion', 'focus-highlight');
      document.documentElement.style.setProperty('--font-scale', 1);
      document.querySelectorAll('.font-btn').forEach(b => b.classList.remove('active'));
      document.querySelector('.font-btn[data-size="1"]').classList.add('active');
      document.querySelectorAll('.toggle-switch input').forEach(t => t.checked = false);
      localStorage.removeItem('empowr_accessibility');
    });

    function save() {
      localStorage.setItem('empowr_accessibility', JSON.stringify({
        dark: document.getElementById('toggle-dark').checked,
        contrast: document.getElementById('toggle-contrast').checked,
        dyslexia: document.getElementById('toggle-dyslexia').checked,
        motion: document.getElementById('toggle-motion').checked,
        focus: document.getElementById('toggle-focus').checked,
        fontScale: document.documentElement.style.getPropertyValue('--font-scale') || 1
      }));
    }

    const saved = JSON.parse(localStorage.getItem('empowr_accessibility') || '{}');
    if (saved.dark) { document.getElementById('toggle-dark').checked = true; document.body.classList.add('dark-mode'); }
    if (saved.contrast) { document.getElementById('toggle-contrast').checked = true; document.body.classList.add('high-contrast'); }
    if (saved.dyslexia) { document.getElementById('toggle-dyslexia').checked = true; document.body.classList.add('dyslexia-font'); }
    if (saved.motion) { document.getElementById('toggle-motion').checked = true; document.body.classList.add('reduce-motion'); }
    if (saved.focus) { document.getElementById('toggle-focus').checked = true; document.body.classList.add('focus-highlight'); }
    if (saved.fontScale) {
      document.documentElement.style.setProperty('--font-scale', saved.fontScale);
      document.querySelectorAll('.font-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.size == saved.fontScale);
      });
    }
    </script>

</footer>