export function loadStoredUser(): unknown | null {
  try {
    const raw = localStorage.getItem('rcc_user')
    return raw ? JSON.parse(raw) : null
  } catch {
    return null
  }
}
