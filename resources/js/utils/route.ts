/**
 * Helper pour générer les URLs des routes
 * Alternative simple à Laravel Ziggy/Wayfinder pour les routes resource
 */

type RouteParams = Record<string, string | number>;

const routes: Record<string, string> = {
    // Girlfriends routes
    'girlfriends.index': '/girlfriends',
    'girlfriends.create': '/girlfriends/create',
    'girlfriends.store': '/girlfriends',
    'girlfriends.show': '/girlfriends/:id',
    'girlfriends.edit': '/girlfriends/:id/edit',
    'girlfriends.update': '/girlfriends/:id',
    'girlfriends.destroy': '/girlfriends/:id',
    
    // Girlfriend Infos routes
    'girlfriend-infos.create': '/girlfriends/:girlfriend/infos/create',
    'girlfriend-infos.store': '/girlfriends/:girlfriend/infos',
    'girlfriend-infos.edit': '/girlfriends/:girlfriend/infos/:info/edit',
    'girlfriend-infos.update': '/girlfriends/:girlfriend/infos/:info',
    'girlfriend-infos.destroy': '/girlfriends/:girlfriend/infos/:info',
};

export function route(name: string, params?: number | RouteParams): string {
    let url = routes[name];
    
    if (!url) {
        console.warn(`Route "${name}" not found`);
        return '/';
    }
    
    // Si params est un nombre, l'utiliser pour :id
    if (typeof params === 'number') {
        url = url.replace(':id', String(params));
    } 
    // Si params est un objet, remplacer tous les paramètres
    else if (params) {
        Object.entries(params).forEach(([key, value]) => {
            url = url.replace(`:${key}`, String(value));
        });
    }
    
    return url;
}

