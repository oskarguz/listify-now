import axios from "axios";

export async function getLoggedUser() {
    try {
        const response = await axios.get('/check-user');
        return response.data;
    } catch (error) {
        throw error;
    }
}
