/**
 * API layer - Corbidev Modal Auth
 * Strictement aucune logique UI ici.
 * Gestion standardisée des appels REST.
 */

const BASE_URL = window.CorbidevModalAuth?.restUrl || '';
const NONCE = window.CorbidevModalAuth?.nonce || '';

/**
 * Helper générique
 */
async function request(endpoint, method = 'GET', body = null) {
    const options = {
        method,
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': NONCE,
        },
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    const response = await fetch(`${BASE_URL}${endpoint}`, options);

    let data;
    try {
        data = await response.json();
    } catch (e) {
        throw {
            success: false,
            code: 'invalid_json',
        };
    }

    if (!response.ok) {
        throw data;
    }

    return data;
}

/**
 * Login
 */
export async function login(payload) {
    return request('/login', 'POST', {
        username: payload.username,
        password: payload.password,
        remember: payload.remember ?? false,
    });
}

/**
 * Logout
 */
export async function logout() {
    return request('/logout', 'POST');
}

/**
 * Mot de passe oublié
 */
export async function lostPassword(user_login) {
    return request('/lost-password', 'POST', {
        user_login,
    });
}

/**
 * Utilisateur courant
 */
export async function getCurrentUser() {
    return request('/me', 'GET');
}