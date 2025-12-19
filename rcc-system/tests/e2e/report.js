import fs from 'node:fs'
import path from 'node:path'

function readJson(p) {
  try {
    const raw = fs.readFileSync(p, 'utf8')
    return JSON.parse(raw)
  } catch {
    return null
  }
}

function formatDate(d = new Date()) {
  return d.toISOString().replace('T', ' ').split('.')[0]
}

function collectTests(json) {
  const projects = {}
  const suites = json?.suites || []
  for (const s of suites) {
    for (const sp of s.suites || []) {
      for (const spec of sp.specs || []) {
        for (const t of spec.tests || []) {
          const project = t.projectName || 'default'
          const status = t.results?.[0]?.status || 'unknown'
          const title = spec.title
          const file = spec.file
          const errors = []
          const result = t.results?.[0]
          const error = result?.error
          if (error) {
            errors.push(typeof error === 'string' ? error : (error.message || JSON.stringify(error)))
          }
          const duration = result?.duration || 0
          projects[project] = projects[project] || { passed: 0, failed: 0, skipped: 0, tests: [] }
          if (status === 'passed') projects[project].passed++
          else if (status === 'skipped') projects[project].skipped++
          else projects[project].failed++
          projects[project].tests.push({ title, file, status, duration, errors })
        }
      }
    }
  }
  return projects
}

function toMarkdown(projects) {
  let md = ''
  md += `# Relatório E2E Playwright\n\n`
  md += `Data: ${formatDate()}\n\n`
  md += `## Resumo por Projeto\n`
  for (const [name, p] of Object.entries(projects)) {
    md += `- ${name}: ✅ ${p.passed} • ❌ ${p.failed} • ⏭️ ${p.skipped}\n`
  }
  md += `\n## Detalhes dos Testes\n`
  for (const [name, p] of Object.entries(projects)) {
    md += `\n### ${name}\n`
    for (const t of p.tests) {
      md += `- ${t.status.toUpperCase()} • ${t.title} (${t.file}) • ${t.duration}ms\n`
      if (t.errors?.length) {
        for (const e of t.errors) {
          md += `  - Erro: ${e}\n`
        }
      }
    }
  }
  md += `\n## Como Reproduzir\n`
  md += `- Executar: ADMIN_EMAIL=\"${process.env.ADMIN_EMAIL || 'admin@example.com'}\" ADMIN_PASSWORD=\"${process.env.ADMIN_PASSWORD || 'secret'}\" BASE_URL=\"${process.env.BASE_URL || 'http://127.0.0.1:8000'}\" SITE_BASE_URL=\"${process.env.SITE_BASE_URL || 'http://127.0.0.1:3002'}\" npm run test:e2e\n`
  md += `- Relatório HTML: playwright-report/index.html\n`
  return md
}

const reportPath = path.resolve('playwright-report', 'results.json')
const json = readJson(reportPath)
if (!json) {
  console.error('Resultados JSON não encontrados em', reportPath)
  process.exitCode = 1
} else {
  const projects = collectTests(json)
  const md = toMarkdown(projects)
  const outPath = path.resolve('ADMIN_E2E_REPORT.md')
  fs.writeFileSync(outPath, md, 'utf8')
  console.log('Relatório gerado em', outPath)
}
