<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Editor de Logo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Editor de Logo')]); ?>
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-emerald-700 mb-4">Editor de Logo</h1>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 card p-4">
            <div class="mb-3">
                <input id="logoFile" type="file" accept="image/png,image/jpeg,image/svg+xml" class="input w-full" />
            </div>
            <div class="relative">
                <div class="absolute inset-0 pointer-events-none grid" style="grid-template-columns: repeat(12, 1fr); grid-template-rows: repeat(6, 1fr); gap: 2px;">
                    <div class="w-full h-full" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);"></div>
                </div>
                <canvas id="editorCanvas" class="w-full rounded-xl border bg-white" style="min-height: 240px; cursor: crosshair"></canvas>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button id="undoBtn" class="btn btn-outline">Desfazer</button>
                    <button id="redoBtn" class="btn btn-outline">Refazer</button>
                    <button id="fitHBtn" class="btn btn-outline">Ajustar Horizontal</button>
                    <button id="fitVBtn" class="btn btn-outline">Ajustar Vertical</button>
                    <button id="fitBtn" class="btn btn-primary">Ajuste Inteligente</button>
                    <button id="narrowBtn" class="btn btn-outline">Estreitar</button>
                    <button id="widenBtn" class="btn btn-outline">Alargar</button>
                    <button id="heightDownBtn" class="btn btn-outline">Diminuir altura</button>
                    <button id="heightUpBtn" class="btn btn-outline">Aumentar altura</button>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="grid gap-3">
                <label class="label">Formato</label>
                <select id="formatSelect" class="input">
                    <option value="png">PNG</option>
                    <option value="jpg">JPG</option>
                    <option value="svg">SVG</option>
                </select>
                <label class="label">Proporção desejada</label>
                <select id="ratioSelect" class="input">
                    <option value="free">Livre</option>
                    <option value="auto">Auto</option>
                    <option value="4:1">4:1 (header)</option>
                    <option value="3:1">3:1</option>
                    <option value="1:1">1:1</option>
                </select>
                <label class="inline-flex items-center gap-2 mt-2"><input id="lockAspect" type="checkbox" class="h-4 w-4 border rounded" /><span>Manter proporção ao redimensionar</span></label>
                <button id="saveBtn" class="btn btn-primary">Salvar como padrão</button>
                <div class="text-sm text-gray-600">A pré-visualização mostra como a logo aparecerá na Home.</div>
            </div>
        </div>
    </div>
    <div class="mt-6 card p-4">
        <div class="text-sm text-gray-600 mb-2">Pré-visualização na Home</div>
        <div class="flex items-center gap-3">
            <img id="previewImg" alt="Preview" class="site-logo site-logo-contrast site-logo-ring rounded-md p-1" />
            <div class="text-xl md:text-2xl font-bold text-emerald-700">Grupo de Oração</div>
        </div>
    </div>
</div>

<script type="module">
const fileInput = document.getElementById('logoFile')
const formatSelect = document.getElementById('formatSelect')
const ratioSelect = document.getElementById('ratioSelect')
const lockAspect = document.getElementById('lockAspect')
const canvas = document.getElementById('editorCanvas')
const ctx = canvas.getContext('2d')
const previewImg = document.getElementById('previewImg')
const undoBtn = document.getElementById('undoBtn')
const redoBtn = document.getElementById('redoBtn')
const fitHBtn = document.getElementById('fitHBtn')
const fitVBtn = document.getElementById('fitVBtn')
const fitBtn = document.getElementById('fitBtn')
const saveBtn = document.getElementById('saveBtn')
const narrowBtn = document.getElementById('narrowBtn')
const widenBtn = document.getElementById('widenBtn')
const heightDownBtn = document.getElementById('heightDownBtn')
const heightUpBtn = document.getElementById('heightUpBtn')

let history = []
let future = []
let image = null
let state = { scale: 1, tx: 0, ty: 0, ratio: 'auto' }
let crop = { x: 32, y: 32, w: 200, h: 120 }
let drag = { active: false, mode: null, handle: null, startX: 0, startY: 0, startCrop: null }

function pushHistory() {
  history.push(JSON.stringify(state))
  if (history.length > 50) history.shift()
  future = []
}

function render() {
  const w = canvas.clientWidth
  const h = Math.max(240, Math.round(w / 2.5))
  canvas.width = w
  canvas.height = h
  ctx.clearRect(0,0,w,h)
  // grid stays via CSS background; draw image
  if (image) {
  const iw = image.naturalWidth || image.width
  const ih = image.naturalHeight || image.height
    const ar = iw / ih
    let targetW = w - 32, targetH = h - 32
    if (state.ratio !== 'auto') {
      const [rw, rh] = state.ratio.split(':').map(Number)
      const desired = rw / rh
      if (desired > ar) {
        // widen horizontally
        targetH = Math.min(targetH, Math.round(targetW / desired))
      } else {
        targetW = Math.min(targetW, Math.round(targetH * desired))
      }
    }
    const s = Math.min(targetW / iw, targetH / ih) * state.scale
    const dw = Math.round(iw * s)
    const dh = Math.round(ih * s)
    const dx = Math.round((w - dw)/2 + state.tx)
    const dy = Math.round((h - dh)/2 + state.ty)
    ctx.imageSmoothingEnabled = true
    ctx.imageSmoothingQuality = 'high'
    ctx.drawImage(image, dx, dy, dw, dh)
    // update preview
    previewImg.src = image.src

    // draw crop rect overlay
    ctx.save()
    ctx.strokeStyle = '#3b82f6'
    ctx.lineWidth = 1.5
    ctx.setLineDash([6,4])
    ctx.strokeRect(crop.x, crop.y, crop.w, crop.h)
    ctx.setLineDash([])
    const hs = 8
    const handles = getHandles()
    ctx.fillStyle = '#3b82f6'
    handles.forEach(hp => { ctx.fillRect(hp.x-hs/2, hp.y-hs/2, hs, hs) })
    ctx.restore()

    // update live preview with cropped output
    try {
      const iw2 = image.naturalWidth || image.width
      const ih2 = image.naturalHeight || image.height
      const sx2 = Math.max(0, Math.round((crop.x - dx) / s))
      const sy2 = Math.max(0, Math.round((crop.y - dy) / s))
      const sw2 = Math.min(iw2, Math.round(crop.w / s))
      const sh2 = Math.min(ih2, Math.round(crop.h / s))
      const out2 = document.createElement('canvas')
      out2.width = Math.max(64, sw2)
      out2.height = Math.max(64, sh2)
      const o2 = out2.getContext('2d')
      o2.imageSmoothingEnabled = true
      o2.imageSmoothingQuality = 'high'
      o2.drawImage(image, sx2, sy2, sw2, sh2, 0, 0, out2.width, out2.height)
      previewImg.src = out2.toDataURL('image/png')
    } catch {}
  }
}

function smartFit() {
  // naive smart fit: center and fit within target with slight padding
  state.scale = 1
  state.tx = 0
  state.ty = 0
  // try to auto-detect content bounds for transparent logos
  try {
    const iw = image.naturalWidth || image.width
    const ih = image.naturalHeight || image.height
    const tmp = document.createElement('canvas')
    tmp.width = iw; tmp.height = ih
    const ictx = tmp.getContext('2d')
    ictx.drawImage(image, 0, 0)
    const data = ictx.getImageData(0,0,iw,ih).data
    let minX = iw, minY = ih, maxX = 0, maxY = 0
    for (let y=0; y<ih; y++) {
      for (let x=0; x<iw; x++) {
        const i = (y*iw + x) * 4
        const a = data[i+3]
        if (a > 10) { // non-transparent
          if (x < minX) minX = x
          if (y < minY) minY = y
          if (x > maxX) maxX = x
          if (y > maxY) maxY = y
        }
      }
    }
    // map to canvas coords using current draw transform
    const w = canvas.clientWidth
    const h = Math.max(240, Math.round(w / 2.5))
    let targetW = w - 32, targetH = h - 32
    const ar = iw/ih
    if (state.ratio !== 'auto') {
      const [rw, rh] = state.ratio.split(':').map(Number)
      const desired = rw / rh
      if (desired > ar) targetH = Math.min(targetH, Math.round(targetW / desired))
      else targetW = Math.min(targetW, Math.round(targetH * desired))
    }
    const s = Math.min(targetW / iw, targetH / ih) * state.scale
    const dw = Math.round(iw * s)
    const dh = Math.round(ih * s)
    const dx = Math.round((w - dw)/2 + state.tx)
    const dy = Math.round((h - dh)/2 + state.ty)
    crop.x = Math.round(dx + minX * s)
    crop.y = Math.round(dy + minY * s)
    crop.w = Math.max(60, Math.round((maxX - minX) * s))
    crop.h = Math.max(40, Math.round((maxY - minY) * s))
  } catch {}
  render()
}

undoBtn.addEventListener('click', () => {
  if (history.length) {
    future.push(JSON.stringify(state))
    state = JSON.parse(history.pop())
    render()
  }
})
redoBtn.addEventListener('click', () => {
  if (future.length) {
    history.push(JSON.stringify(state))
    state = JSON.parse(future.pop())
    render()
  }
})
fitHBtn.addEventListener('click', () => {
  pushHistory()
  state.ratio = '4:1'
  smartFit()
})
fitVBtn.addEventListener('click', () => {
  pushHistory()
  state.ratio = '1:1'
  smartFit()
})
fitBtn.addEventListener('click', () => {
  pushHistory()
  state.ratio = 'auto'
  smartFit()
})

narrowBtn.addEventListener('click', () => {
  pushHistory()
  crop.w = Math.max(32, crop.w - 40)
  render()
})
widenBtn.addEventListener('click', () => {
  pushHistory()
  crop.w = crop.w + 40
  render()
})
heightDownBtn.addEventListener('click', () => {
  pushHistory()
  crop.h = Math.max(32, crop.h - 40)
  render()
})
heightUpBtn.addEventListener('click', () => {
  pushHistory()
  crop.h = crop.h + 40
  render()
})

fileInput.addEventListener('change', (e) => {
  const f = e.target.files?.[0]
  if (!f) return
  const url = URL.createObjectURL(f)
  image = new Image()
  image.onload = () => { smartFit() }
  image.src = url
})

ratioSelect.addEventListener('change', () => {
  const v = ratioSelect.value
  state.ratio = v === 'free' ? 'auto' : v
  render()
})

saveBtn.addEventListener('click', async () => {
  const f = fileInput.files?.[0]
  const fd = new FormData()
  // produce cropped blob for bitmap formats
  let fmt = (formatSelect.value||'png')
  if (image) {
    const iw = image.naturalWidth || image.width
    const ih = image.naturalHeight || image.height
    // recompute draw transform (same as render)
    const w = canvas.width
    const h = canvas.height
    let targetW = w - 32, targetH = h - 32
    const ar = iw/ih
    if (state.ratio !== 'auto') {
      const [rw, rh] = state.ratio.split(':').map(Number)
      const desired = rw / rh
      if (desired > ar) targetH = Math.min(targetH, Math.round(targetW / desired))
      else targetW = Math.min(targetW, Math.round(targetH * desired))
    }
    const s = Math.min(targetW / iw, targetH / ih) * state.scale
    const dw = Math.round(iw * s)
    const dh = Math.round(ih * s)
    const dx = Math.round((w - dw)/2 + state.tx)
    const dy = Math.round((h - dh)/2 + state.ty)
    const sx = Math.max(0, Math.round((crop.x - dx) / s))
    const sy = Math.max(0, Math.round((crop.y - dy) / s))
    const sw = Math.min(iw, Math.round(crop.w / s))
    const sh = Math.min(ih, Math.round(crop.h / s))
    const out = document.createElement('canvas')
    out.width = Math.max(64, sw)
    out.height = Math.max(64, sh)
    const octx = out.getContext('2d')
    octx.imageSmoothingEnabled = true
    octx.imageSmoothingQuality = 'high'
    try {
      octx.drawImage(image, sx, sy, sw, sh, 0, 0, out.width, out.height)
    } catch {}
    const quality = fmt === 'jpg' ? 0.85 : 0.92
    const blob = await new Promise(resolve => out.toBlob(resolve, fmt === 'jpg' ? 'image/jpeg' : 'image/png', quality))
    if (blob) fd.append('file', new File([blob], `logo_${Date.now()}.${fmt==='jpg'?'jpg':'png'}`))
    else if (f) fd.append('file', f)
  } else if (f) {
    fd.append('file', f)
  }
  fd.append('format', (formatSelect.value||'png'))
  fd.append('settings', JSON.stringify(state))
  fd.append('history', JSON.stringify(history))
  const res = await fetch('/admin/logo', { method: 'POST', body: fd, headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content ?? '') } })
  if (res.ok) {
    const j = await res.json()
    previewImg.src = j.logo_url || previewImg.src
    alert('Logo salva com sucesso!')
  } else {
    alert('Falha ao salvar.')
  }
})

window.addEventListener('resize', render)

function getHandles() {
  const x = crop.x, y = crop.y, w = crop.w, h = crop.h
  return [
    { id:'nw', x, y }, { id:'n', x:x+w/2, y }, { id:'ne', x:x+w, y },
    { id:'e', x:x+w, y:y+h/2 }, { id:'se', x:x+w, y:y+h }, { id:'s', x:x+w/2, y:y+h },
    { id:'sw', x, y:y+h }, { id:'w', x, y:y+h/2 }
  ]
}

function hitHandle(mx,my) {
  const hs = 8
  for (const h of getHandles()) {
    if (Math.abs(mx - h.x) <= hs && Math.abs(my - h.y) <= hs) return h.id
  }
  return null
}

canvas.addEventListener('mousedown', (e) => {
  const rect = canvas.getBoundingClientRect()
  const mx = e.clientX - rect.left
  const my = e.clientY - rect.top
  const h = hitHandle(mx,my)
  drag.active = true
  drag.mode = h ? 'resize' : (mx>=crop.x && mx<=crop.x+crop.w && my>=crop.y && my<=crop.y+crop.h ? 'move' : null)
  drag.handle = h
  drag.startX = mx
  drag.startY = my
  drag.startCrop = { ...crop }
})

canvas.addEventListener('mousemove', (e) => {
  if (!drag.active || !drag.mode) return
  const rect = canvas.getBoundingClientRect()
  const mx = e.clientX - rect.left
  const my = e.clientY - rect.top
  const dx = mx - drag.startX
  const dy = my - drag.startY
  if (drag.mode === 'move') {
    crop.x = drag.startCrop.x + dx
    crop.y = drag.startCrop.y + dy
  } else if (drag.mode === 'resize') {
    const sc = drag.startCrop
    switch (drag.handle) {
      case 'nw': crop.x = sc.x + dx; crop.y = sc.y + dy; crop.w = sc.w - dx; crop.h = sc.h - dy; break
      case 'n': crop.y = sc.y + dy; crop.h = sc.h - dy; break
      case 'ne': crop.y = sc.y + dy; crop.w = sc.w + dx; crop.h = sc.h - dy; break
      case 'e': crop.w = sc.w + dx; break
      case 'se': crop.w = sc.w + dx; crop.h = sc.h + dy; break
      case 's': crop.h = sc.h + dy; break
      case 'sw': crop.x = sc.x + dx; crop.w = sc.w - dx; crop.h = sc.h + dy; break
      case 'w': crop.x = sc.x + dx; crop.w = sc.w - dx; break
    }
    // optional aspect lock
    if (lockAspect.checked && ratioSelect.value !== 'free') {
      const [rw, rh] = (ratioSelect.value==='auto'?['auto','auto']:ratioSelect.value.split(':'))
      if (rw!=='auto') {
        const desired = Number(rw)/Number(rh)
        crop.h = Math.round(crop.w / desired)
      }
    }
    crop.w = Math.max(32, crop.w)
    crop.h = Math.max(32, crop.h)
  }
  render()
})

canvas.addEventListener('mouseup', () => { drag.active = false; drag.mode = null })
canvas.addEventListener('mouseleave', () => { drag.active = false; drag.mode = null })
</script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/rcc-system/resources/views/admin/logo-editor.blade.php ENDPATH**/ ?>