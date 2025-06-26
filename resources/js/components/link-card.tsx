import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import { Pill } from '@/components/ui/pill';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { ArrowUpRight, Globe, User } from 'lucide-react';
import TextLink from './text-link';
import LinkData = App.Data.LinkData;

export default function LinkCard({ link, className }: { link: LinkData; className?: string }) {
    return (
        <Card className={cn('w-full', className)}>
            <CardHeader className="flex-row items-baseline justify-between">
                <TextLink href={route('links.show', link.id)} variant="ghost">
                    <CardTitle>{link.title}</CardTitle>
                </TextLink>
                <a href={link.url} target="_blank" className="text-muted-foreground hover:text-foreground">
                    <ArrowUpRight></ArrowUpRight>
                </a>
            </CardHeader>
            <CardContent className="space-y-4">
                {link.description && (
                    <pre className="font-sans whitespace-pre-wrap">
                        {link.description.length > 256 ? link.description.substring(0, 255) + '...' : link.description}
                    </pre>
                )}
                {link.tags.length > 0 && (
                    <div className="flex flex-wrap gap-2">
                        {link.tags.map((tag) => (
                            <Link href={route('links.index', { tags: [tag.id] })} key={tag.id}>
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
                        <TextLink href={route('links.index', { author_id: link.author.id })} variant="ghost">
                            {link.author.name}
                        </TextLink>
                    </div>
                ) : (
                    <div />
                )}
                {link.published_at && (
                    <div className="text-muted-foreground flex items-center gap-2 text-sm">
                        {link.is_public && <Globe size={16} />}
                        <span>
                            Published : <Datetime datetime={new Date(link.published_at)} />
                        </span>
                    </div>
                )}
            </CardFooter>
        </Card>
    );
}
