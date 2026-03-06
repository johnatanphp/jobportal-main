<div class="formwraper" style="background: #F8F9FA;">
  <div style="border-bottom: 1px solid #DFDFDF;height: 51px;"></div>
  <div class="content-empty">
    <div>
      <?php if ($init == 0): ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="186" height="123" viewBox="0 0 186 123" fill="none">
          <g filter="url(#filter0_dd_910_8938)">
            <rect x="51" y="10" width="82" height="103" rx="12" fill="white"/>
            <rect x="51.5" y="10.5" width="81" height="102" rx="11.5" stroke="#BCBCBC"/>
          </g>
          <path d="M87.95 37L67.5 53H120.25C121.833 52.6667 125 51.1 125 47.5L108.85 31L96.5 45L87.95 37Z" fill="#DEE0E4"/>
          <rect x="60.5" y="19.5" width="64" height="33" rx="5.5" stroke="#BCBCBC"/>
          <rect x="60.5" y="82.5" width="27" height="21" rx="5.5" fill="white" stroke="#BCBCBC"/>
          <path d="M66 52.5L87.1322 36L95.7603 44L108.223 30L124.5 48" stroke="#BCBCBC"/>
          <path d="M61 60H125" stroke="#BCBCBC" stroke-linecap="round"/>
          <path d="M61 76H125" stroke="#BCBCBC" stroke-linecap="round"/>
          <path d="M61 68H89" stroke="#BCBCBC" stroke-linecap="round"/>
          <path d="M97 68H125" stroke="#BCBCBC" stroke-linecap="round"/>
          <circle cx="73.5" cy="31.5" r="4" fill="#DEE0E4" stroke="#BCBCBC"/>
          <path d="M48.5 92H10M176 92H137.5" stroke="#BCBCBC" stroke-linecap="round"/>
          <g filter="url(#filter1_dd_910_8938)">
            <circle cx="123" cy="72" r="24" fill="#DEE0E4"/>
            <circle cx="123" cy="72" r="23.5" stroke="#BCBCBC"/>
          </g>
          <rect x="139.318" y="87.5116" width="4" height="13" transform="rotate(-48.7565 139.318 87.5116)" fill="#DEE0E4"/>
          <rect x="143.969" y="93.5839" width="7" height="16.9794" rx="3.5" transform="rotate(-48.7565 143.969 93.5839)" fill="white" stroke="#BCBCBC"/>
          <ellipse cx="123" cy="72.5" rx="23" ry="22.5" fill="white"/>
          <circle cx="123" cy="72" r="17.5" fill="#DEE0E4" stroke="#BCBCBC"/>
          <defs>
            <filter id="filter0_dd_910_8938" x="47" y="7" width="90" height="111" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
              <feFlood flood-opacity="0" result="BackgroundImageFix"/>
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
              <feOffset dy="1"/>
              <feGaussianBlur stdDeviation="2"/>
              <feComposite in2="hardAlpha" operator="out"/>
              <feColorMatrix type="matrix" values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.05 0"/>
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_910_8938"/>
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
              <feOffset dy="1"/>
              <feGaussianBlur stdDeviation="2"/>
              <feComposite in2="hardAlpha" operator="out"/>
              <feColorMatrix type="matrix" values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.1 0"/>
              <feBlend mode="normal" in2="effect1_dropShadow_910_8938" result="effect2_dropShadow_910_8938"/>
              <feBlend mode="normal" in="SourceGraphic" in2="effect2_dropShadow_910_8938" result="shape"/>
            </filter>
            <filter id="filter1_dd_910_8938" x="95" y="45" width="56" height="56" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
              <feFlood flood-opacity="0" result="BackgroundImageFix"/>
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
              <feOffset dy="1"/>
              <feGaussianBlur stdDeviation="2"/>
              <feComposite in2="hardAlpha" operator="out"/>
              <feColorMatrix type="matrix" values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.05 0"/>
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_910_8938"/>
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
              <feOffset dy="1"/>
              <feGaussianBlur stdDeviation="2"/>
              <feComposite in2="hardAlpha" operator="out"/>
              <feColorMatrix type="matrix" values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.1 0"/>
              <feBlend mode="normal" in2="effect1_dropShadow_910_8938" result="effect2_dropShadow_910_8938"/>
              <feBlend mode="normal" in="SourceGraphic" in2="effect2_dropShadow_910_8938" result="shape"/>
            </filter>
          </defs>
        </svg>
        <label style="font-size: 15px;display: block;">Aún no hay ningún postulante</label>
        <?php if (has_permission_action('recruitment_tray', 'add_candidates')): ?>
            <button class="btn btn-sm btn-primary btn-style-1 add-candidate" 
                    data-target="#modal-add-candidates">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 25 24" fill="none" style="display: inline-block;">
                <path d="M11.5 13H5.5V11H11.5V5H13.5V11H19.5V13H13.5V19H11.5V13Z" fill="white"/>
            </svg>
            <span style="vertical-align: top;">Agregar</span>
            </button>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>   