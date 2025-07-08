import LinkMetaDataCard from '@/components/link-metadata-card';
import TextLink from '@/components/text-link';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import { Pill } from '@/components/ui/pill';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { ArrowUpRight, SquareUser, User } from 'lucide-react';
import CommunityLinkResource = App.Data.Resources.CommunityLinkResource;

export default function CommunityLinkCard({ link, className }: { link: CommunityLinkResource; className?: string }) {
    return (
        <Card className={cn('w-full', className)}>
            <CardHeader>
                <div className="flex items-center justify-between">
                    <CardTitle>{link.title}</CardTitle>
                    <a href={link.url} target="_blank">
                        <ArrowUpRight></ArrowUpRight>
                    </a>
                </div>
                <CardDescription>
                    <Link href={route('community-links.index', { user: link.user.uuid })}>
                        <div className="flex items-center space-x-1">
                            <SquareUser size={16} />
                            <span> {link.user.username}</span>
                        </div>
                    </Link>
                </CardDescription>
            </CardHeader>

            <CardContent className="space-y-4">
                {link.description && (
                    <pre className="font-sans whitespace-pre-wrap">
                        {link.description.length > 256 ? link.description.substring(0, 255) + '...' : link.description}
                    </pre>
                )}

                {link.metadata && <LinkMetaDataCard metadata={link.metadata} url={link.url} />}

                {link.tags.length > 0 && (
                    <div className="flex flex-wrap gap-2">
                        {link.tags.map((tag) => (
                            <Link href={route('community-links.index', { tags: [tag.label] })} key={tag.uuid}>
                                <Pill>{tag.label}</Pill>
                            </Link>
                        ))}
                    </div>
                )}
            </CardContent>
            <CardFooter className="flex justify-between">
                {link.author ? (
                    <div className="flex gap-x-4">
                        <User />
                        <TextLink href={route('community-links.index', { author: link.author.name })} variant="ghost">
                            {link.author.name}
                        </TextLink>
                    </div>
                ) : (
                    <div />
                )}
                {link.published_at && (
                    <div className="text-muted-foreground flex items-center gap-2 text-sm">
                        <span>
                            Published : <Datetime datetime={new Date(link.published_at)} />
                        </span>
                    </div>
                )}
            </CardFooter>
        </Card>
    );
}
