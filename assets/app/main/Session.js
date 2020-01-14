// Class responsável por lidar com
// as configurações de sessão
export default class Session {
    set(id, value) {
        if (typeof value === 'object') value = JSON.stringify(value);

        localStorage.setItem(id, value);
    }

    get(id) {
        const value = localStorage.getItem(id);
        try {
            return JSON.parse(value);
        } catch (e) {
            return value;
        }
    }

    destroy()
    {
        localStorage.clear();
    }
} // END >> Class::Session
