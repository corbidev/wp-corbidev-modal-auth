export async function login(username, password) {

  const response = await fetch(window.CDA_CONFIG.rest_url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': window.CDA_CONFIG.nonce
    },
    body: JSON.stringify({
      username,
      password
    })
  })

  const data = await response.json()

  return data
}