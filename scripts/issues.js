async function fetchAllIssues() {
    let url = 'issues.php/issues'
    const response = await fetch(url);
    if (response.ok) {
        // Returns the response as a string
        console.log(response);
        return response.text();

        // If any unexpected errors happen while fetching, an error is thrown
    } else {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }
}

export { fetchAllIssues }