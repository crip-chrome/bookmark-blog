import fs from 'fs';

const Walk = (dir, originalDir = false) => {
    let results = new Array();

    if (!originalDir)
        originalDir = dir;

    fs.readdirSync(dir).forEach(file => {
        const fullFile = `${dir}/${file}`;
        const stat = fs.statSync(fullFile);
        if (stat && stat.isDirectory())
            results = results.concat(Walk(fullFile, originalDir));
        else
            results.push(fullFile.replace(originalDir, '').replace(/^\/|\/$/g, ''));
    });

    return results;
};

export default Walk;