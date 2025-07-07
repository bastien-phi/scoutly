import LinkMetaData = App.Data.LinkMetaData;

export default function LinkMetaDataCard({ metadata, url }: { metadata: LinkMetaData; url: string }) {
    return (
        <div className="flex justify-center">
            <div className="aspect-video w-full max-w-128">
                {metadata.html && (
                    <div className="overflow-hidden rounded-xl border dark:border-0" dangerouslySetInnerHTML={{ __html: metadata.html }} />
                )}
                {!metadata.html && metadata.image && (
                    <a href={url} target="_blank">
                        <div className="relative overflow-hidden rounded-xl border dark:border-0">
                            <img
                                src={metadata.image}
                                alt={metadata.title || metadata.description || ''}
                                className="aspect-video w-full max-w-128 object-cover"
                            />
                            {(metadata.title || metadata.description) && (
                                <div className="dark:text-background absolute bottom-0 w-full truncate bg-white/90 p-2">
                                    {metadata.title || metadata.description}
                                </div>
                            )}
                        </div>
                    </a>
                )}
            </div>
        </div>
    );
}
